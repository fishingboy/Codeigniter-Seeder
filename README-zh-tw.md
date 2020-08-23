# Codeigniter-Seeder

## 安裝
```
composer require fishingboy/codeigniter-seeder
```

## 使用方法
1. 建立 application/controller/Seeder.php
    ```php
    <?php
    use fishingboy\ci_seeder\CI_Seeder_Controller;
    class Seeder extends CI_Seeder_Controller { }
    ```
    
2. 建立 application/seeders 資料夾

3. 建立 application/seeders/Sample_seeder.php
    ```php
    <?php
    
    use fishingboy\ci_seeder\CI_Seeder_base;
    
    class Sample_seeder extends CI_Seeder_base
    {
        /**
         * 執行順序 (大的排前面)
         * @var integer
         */
        public $priority = 100;
    
        /**
         * 塞資料
         * @return integer 新增資料筆數
         */
        public function run()
        {
            $this->CI->db->insert("users", [
                'name' => fishingboy,
            ]);
            return 1;
        }
    }
    
    ```
    
4. 進入 command line 專案目錄底下，執行 `php index.php seeder`
    ```shell
    $ php index.php seeder
    seeder (資料填充)
    php index.php seeder                   -- 看指令
    php index.php seeder run               -- 執行
    php index.php seeder run {seeder_name} -- 執行單一 Seeder
    php index.php seeder ls                -- 看目前 Seeder 的狀態
    -- 
    php index.php seeder run Sample_seeder                     (priority: 100)
    ```
    
5. 執行你要的 seeder
   ```shell
   $ php index.php seeder run Log_seeder
   Seed [Sample_seeder] 執行完成，建立 1 筆資料. 

   ```

## 語言
[en-us](README.md) / 
[zh-tw](README-zh-tw.md)

   