<?php

namespace fishingboy\ci_seeder;

/**
 * Seeder
 */
class CI_Seeder_Controller extends \CI_Controller
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
        $this->_nl = ($this->input->is_cli_request()) ? "\n" : "<br>";

        // 限定本機或 Command Line 使用
        if ( ! $this->input->is_cli_request() &&
            $_SERVER['REMOTE_ADDR'] != '127.0.0.1' &&
            $_SERVER['REMOTE_ADDR'] != '::1' &&
            false === strpos($_SERVER['REMOTE_ADDR'], '192.168.')
            && ENVIRONMENT != "development"
        )
        {
            echo "No Permission !!!";
            exit;
        }

        // 載入資料庫
        $this->load->database();

        // 指定 Seed 路徑
        $this->seeder_path = FCPATH . "application/seeders";
    }

    public function index()
    {
        echo "seeder (資料填充)

php index.php seeder                   -- help 
php index.php seeder run               -- execute seeder 
php index.php seeder run {seeder_name} -- execute One Seeder
php index.php seeder ls                -- check seeder status 

--

";
        $this->ls();
    }

    /**
     * 執行種子
     */
    public function run($seeder_name = "")
    {
        $seeders = $this->findSeeders($seeder_name);
        foreach ($seeders as $seeder_obj) {
            $count = $seeder_obj->run();
            $seeder_name = get_class($seeder_obj);
            echo "Seed [$seeder_name] complete, carete $count rows. \n";
        }
    }

    /**
     * 顯示種子列表
     */
    public function ls()
    {
        $seeders = $this->findSeeders();
        foreach ($seeders as  $seeder_name => $seeder_obj) {
            echo sprintf("php index.php seeder run %s (priority: %3d)\n", str_pad(get_class($seeder_obj), 30), $seeder_obj->priority);
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

        // 依優先權排序
        usort($seeders, function ($a, $b)
        {
            if ($a->priority == $b->priority) {
                return (get_class($a) > get_class($b)) ? 1 : -1;
            }
            return  ($a->priority > $b->priority) ? -1 : 1;
        });

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
