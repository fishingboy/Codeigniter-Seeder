<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Seeder
 */
class CI_Seeder_Controller extends CI_Controller
{
    /**
     * 換行字元
     * @var string
     */
    private $_nl;

    /**
     * 種子目錄
     * @var string
     */
    private $seeder_path;

    public function __construct()
    {
        parent::__construct();

        // // 判斷換行字元
        $this->_nl = (is_cli()) ? "\n" : "<br>";

        // 限定本機或 Command Line 使用
        if ( ! is_cli() && 
            $_SERVER['REMOTE_ADDR'] != '127.0.0.1' && 
            $_SERVER['REMOTE_ADDR'] != '::1' &&
            false !== strpos($_SERVER['REMOTE_ADDR'], '192.168.')
            )
        {
            echo "No Permission !!!";
            exit;
        }

        // 載入資料庫
        $this->load->database();

        // 指定 Seed 路徑
        $this->seeder_path = __DIR__ . "/../seeders";
    }

    public function index()
    {
        $ignore_arr = ['__construct', 'get_instance', 'index', 'findSeeders', 'findOneSeeder'];
        $method_arr = get_class_methods(__CLASS__);
        foreach ($method_arr as $method)
        {
            if ( ! in_array($method, $ignore_arr))
            {
                echo "php index.php {$this->router->class} {$method} {$this->_nl}";
            }
        }
    }

    /**
     * 執行種子
     */
    public function run($seeder_name = "")
    {
        $seeders = $this->findSeeders($seeder_name);
        foreach ($seeders as  $seeder_name => $seeder_obj) {
            $count = $seeder_obj->run();
            echo "Seed [$seeder_name] 執行完成，建立 $count 筆資料. \n";
        }
    }

    /**
     * 顯示種子列表
     * @return [type] [description]
     */
    public function list()
    {
        $seeders = $this->findSeeders();
        foreach ($seeders as  $seeder_name => $seeder_obj) {
            echo "php index.php seeder run $seeder_name\n";
        }
    }

    /**
     * 取得所有種子
     * @return array
     */
    private function findSeeders($seeder_name = "")
    {
        // 找單一種子
        if ($seeder_name) {
            $seeder_obj = $this->findOneSeeder($seeder_name);
            return $seeder_obj ? [$seeder_name => $seeder_obj] : false;
        }

        // 找目錄下所有種子
        $dir = opendir($this->seeder_path);
        $seeders = [];
        while ($file_name = readdir($dir)) {
            if ( ! preg_match("/\.php$/", $file_name)) {
                continue;
            }

            $class_name = str_replace(".php", '', $file_name);
            $seeder_obj = $this->findOneSeeder($class_name);
            if ($seeder_obj) {
                $seeders[$class_name] = $seeder_obj;
            }
        }
        return $seeders;
    }

    /**
     * 尋找單一種子
     * @param  string $class_name 種子名稱
     * @return array
     */
    private function findOneSeeder($class_name)
    {
        $seeder_file = $this->seeder_path . "/" . $class_name . '.php';
        
        if (preg_match("/_seeder$/", $class_name) && file_exists($seeder_file)) {
            // 生成種子實體
            include_once ($seeder_file);
            $seeder_obj = new $class_name();
            return $seeder_obj;
        }
        return false;
    }
}
