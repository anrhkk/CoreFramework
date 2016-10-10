<?php namespace helper;
class Backup
{
    //错误信息
    protected $error;

    //获取目录，如果目录中不含有lock.php为不合法目录（比如备份中断）
    public static function getBackupDir($dir)
    {
        $data = [];
        foreach (Dir::tree($dir) as $d) {
            if (is_file($d['path'] . '/lock.php')) {
                $data[] = $d;
            }
        }

        return $data;
    }

    //还原数据
    public static function recoveryInit($config)
    {
        Cache::store('_recovery_', '[del]');
        if (!is_dir($config['dir'])) {
            self::$error = '目录不存在';

            return false;
        }
        foreach (Dir::tree($config['dir']) as $f) {
            if ($f['basename'] == 'config.php' || $f['basename'] == 'structure.php' || $f['basename'] == 'lock.php') {
                //不运行执行的文件
                continue;
            } else {
                $file[] = $f['path'];
            }
        }
        $cache['config'] = $config;
        $cache['file'] = $file;
        $cache['totalfile'] = count($file);
        Cache::store('_recovery_', $cache);

        //还原表结构
        require $config['dir'] . '/structure.php';

        return true;
    }

    //执行还原
    public static function recovery()
    {
        $files = $cache = Cache::store('_recovery_');
        foreach ($files['file'] as $id => $f) {
            require $f;
            unset($cache['file'][$id]);
            Cache::store('_recovery_', $cache);
            //完成比例
            $bl = (intval(($cache['totalfile'] - count($cache['file'])) / $cache['totalfile'] * 100));
            response($bl . '%还原完毕', 1, __URL__, $cache['config']['time']);
        }
        Cache::store('_recovery_', '[del]');
        response('所有分卷还原完毕...', 1, $cache['config']['url'], 1);
    }

    //备份配置
    public static function backupInit($config)
    {
        Cache::store('_backup_', '[del]');
        //创建目录
        if (!is_dir($config['dir']) && !mkdir($config['dir'], 0755, true)) {
            response('目录创建失败', 0, $config['url']);
        }
        $table = Db::getAllTableInfo();
        $table = $table['table'];
        foreach ($table as $d) {
            //limit起始数
            $table[$d['tablename']]['first'] = 0;
            //文件编号
            $table[$d['tablename']]['fileId'] = 1;
        }
        $cache['table'] = $table;
        $cache['config'] = $config;


        //备份表结构
        $tables = Db::getAllTableInfo();
        $sql = "<?php if(!defined('CORE_PATH'))EXIT;\n";
        foreach ($tables['table'] as $table => $data) {
            $createSql = Db::select("SHOW CREATE TABLE $table");
            $sql .= "Db::execute(\"DROP TABLE IF EXISTS {$table}\");\n";
            $sql .= "Db::execute(\"{$createSql[0]['Create Table']}\");\n";
        }

        if (file_put_contents($config['dir'] . '/structure.php', $sql)) {
            file_put_contents($config['dir'] . '/config.php', "<?php return " . var_export($config, true) . ";");
            Cache::store('_backup_', $cache);

            return true;
        } else {
            Cache::store('_backup_' . '[del]');
            self::$error = '表结构备份失败';

            return false;
        }
    }

    //执行备份
    public static function backup()
    {
        $cache = Cache::store('_backup_');
        foreach ($cache['table'] as $table => $config) {
            $sql = "<?php if(!defined('CORE_PATH'))EXIT;\n";
            do {
                $data = Db::table($table, true)->limit($config['first'], 20)->findAll();
                $cache['table'][$table]['first'] = $config['first'] + 20;
                //表中无数据
                if (empty($data)) {
                    unset($cache['table'][$table]);
                    Cache::store('_backup_', $cache);

                    response("$table 备份完成", 1, __URL__, $cache['config']['time']);
                } else {
                    foreach ($data as $d) {
                        $field = $value = [];
                        foreach ($d as $f => $v) {
                            $field[] = $f;
                            $value[] = is_numeric($v) ? $v : "'" . addslashes($v) . "'";
                        }
                        //表名
                        $sql .= "Db::execute(\"REPLACE INTO $table (`" . implode("`,`", $field) . "`)
						VALUES(" . implode(",", $value) . ")\");\n";
                    }
                }

                //检测本次备份是否超出分卷大小
                if (strlen($sql) > $cache['config']['size']) {
                    $cache['table'][$table]['fileId'] += 1;
                    //写入备份
                    $file = $cache['config']['dir'] . '/' . $table . '_' . $config['fileId'] . '.php';
                    Cache::store('_backup_', $cache);

                    file_put_contents($file, $sql);
                    response("$table 第{$cache['table'][$table]['fileId']}卷备份完成", 1, __URL__, $cache['config']['time']);
                }
            } while (true);
        }
        Cache::store('_backup_', '[del]');
        touch($cache['config']['dir'] . '/lock.php');
        response('完成所有数据备份...', 1, $cache['config']['url'], 1);
    }

    //返回错误
    public static function getError()
    {
        return self::$error;
    }
}