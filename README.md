# Codeigniter-Seeder

## Installation
```
composer require fishingboy/codeigniter-seeder
```

## Usage
1. Create file: `application/controller/Seeder.php`
    ```php
    <?php
    use fishingboy\ci_seeder\CI_Seeder_Controller;
    class Seeder extends CI_Seeder_Controller { }
    ```
    
2. Create folder: `application/seeders`

3. Create sample file: `application/seeders/Sample_seeder.php`
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
    
4. Seeder help
    ```shell
    seeder
    
    php index.php seeder                   -- help 
    php index.php seeder run               -- execute seeder 
    php index.php seeder run {seeder_name} -- execute One Seeder
    php index.php seeder ls                -- check seeder status 
    
    --

    php index.php seeder run Sample_seeder                     (priority: 100)
    ```
    
5. Execute seeder
   ```shell
   $ php index.php seeder run Log_seeder
   Seed [Sample_seeder] complete, carete 1 rows. 

   ```
## Language
[en-us](README.md) / 
[zh-tw](README-zh-tw.md)

