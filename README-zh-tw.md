# CodeIgniter Seeder：PHP 資料庫種子資料工具

[![Packagist Version](https://img.shields.io/packagist/v/fishingboy/codeigniter-seeder.svg)](https://packagist.org/packages/fishingboy/codeigniter-seeder)
[![Downloads](https://img.shields.io/packagist/dt/fishingboy/codeigniter-seeder.svg?label=Downloads)](https://packagist.org/packages/fishingboy/codeigniter-seeder)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

一個輕量的 CodeIgniter Seeder 套件，讓你用可重複執行的 PHP class 建立資料庫種子資料，並透過 command line 列出與執行 seeders。

## 語言

[en-us](README.md) /
[zh-tw](README-zh-tw.md)

## 為什麼使用這個套件？

CodeIgniter 預設沒有 Laravel 風格的 database seeder 工作流程。這個套件提供一個小型 seeder controller 與 base class，讓既有 CodeIgniter 專案可以用穩定的 CLI 指令建立開發資料、查詢表、角色、設定與 demo records。

它刻意維持簡單：seed files 是一般 PHP class，資料寫入使用 CodeIgniter 原本的 database layer，導入既有專案時只需要少量設定。

## 功能特色

- 在既有 CodeIgniter 專案加入資料填充流程，不需要自己寫 runner。
- 可執行全部 seeders，也可依 class name 執行單一 seeder。
- 使用簡單的 `$priority` 屬性控制執行順序。
- 可從 CLI 列出目前可用 seeders 與 priority。
- Seeder 內可透過 `$this->CI` 使用原本的 CodeIgniter instance。
- Browser 存取限制在本機、私有網段或 development 環境。

## 安裝

```shell
composer require fishingboy/codeigniter-seeder
```

## 快速開始

### 1. 建立 seeder controller

建立 `application/controllers/Seeder.php`：

```php
<?php

use fishingboy\ci_seeder\CI_Seeder_Controller;

class Seeder extends CI_Seeder_Controller
{
}
```

### 2. 建立 seeders 資料夾

在 CodeIgniter 專案中建立：

```text
application/seeders
```

### 3. 建立範例 seeder

建立 `application/seeders/Sample_seeder.php`：

```php
<?php

use fishingboy\ci_seeder\CI_Seeder_base;

class Sample_seeder extends CI_Seeder_base
{
    /**
     * 執行順序，數字越大越早執行。
     *
     * @var integer
     */
    public $priority = 100;

    /**
     * 填入種子資料。
     *
     * @return integer 新增資料筆數。
     */
    public function run()
    {
        $this->CI->db->insert("users", [
            'name' => 'fishingboy',
        ]);

        return 1;
    }
}
```

### 4. 執行 seeder

```shell
php index.php seeder run Sample_seeder
```

輸出範例：

```text
Seed [Sample_seeder] complete, carete 1 rows.
```

## 指令

```shell
php index.php seeder                   # 顯示說明與 seeder 清單
php index.php seeder run               # 執行全部 seeders
php index.php seeder run {seeder_name} # 執行單一 seeder
php index.php seeder ls                # 列出 seeder 狀態
```

清單輸出範例：

```text
php index.php seeder run Sample_seeder                     (priority: 100)
```

## 實務範例

Seeder 適合放穩定且可重複建立的資料，例如本機、測試或 staging 環境都需要的角色、權限、設定或查詢表資料。

```php
<?php

use fishingboy\ci_seeder\CI_Seeder_base;

class Role_seeder extends CI_Seeder_base
{
    public $priority = 200;

    public function run()
    {
        $roles = [
            ['name' => 'admin'],
            ['name' => 'editor'],
            ['name' => 'member'],
        ];

        foreach ($roles as $role) {
            $this->CI->db->insert('roles', $role);
        }

        return count($roles);
    }
}
```

執行：

```shell
php index.php seeder run Role_seeder
```

## Seeder 命名規則

- Seeder 檔案必須放在 `application/seeders`。
- Seeder class name 必須以 `_seeder` 結尾。
- Seeder 檔名必須與 class name 相同，例如 `Sample_seeder.php`。
- Seeder 應繼承 `fishingboy\ci_seeder\CI_Seeder_base`。
- `$priority` 數字越大，越早執行。

## 比較

| 方式 | 適合用途 | 取捨 |
| --- | --- | --- |
| Codeigniter-Seeder | 既有 CodeIgniter 專案的可重複種子資料 | 需要加入 seeder controller |
| CodeIgniter migrations | schema 變更與資料庫版本控管 | 不是專門處理資料填充 |
| 一次性 SQL 檔 | 手動匯入或交付資料 | 較難用 PHP 重用、排序與 review |
| 自訂 CLI scripts | 高度客製的專案流程 | 需要維護更多程式碼 |

## FAQ

### 這個套件會取代 CodeIgniter migrations 嗎？

不會。Migrations 比較適合 schema 變更；這個套件適合新增或準備資料，例如 users、roles、permissions、settings、lookup tables 與 demo records。

### 可以只執行單一 seeder 嗎？

可以，傳入 seeder class name：

```shell
php index.php seeder run Sample_seeder
```

### 如何控制執行順序？

在每個 seeder 設定 public `$priority` 屬性。數字越大，越早執行。

### Seeder 可以使用 CodeIgniter models、libraries 和 database object 嗎？

可以。Seeder 可透過 `$this->CI` 取得 CodeIgniter instance，因此能使用 `$this->CI->db`、載入 models，或呼叫既有 application services。

### 可以從瀏覽器執行嗎？

Controller 主要設計給 CLI 使用。瀏覽器存取會限制在本機、私有網段或 `development` 環境。

## 關鍵字

CodeIgniter seeder、CodeIgniter database seeding、PHP seeder、CodeIgniter 種子資料、CodeIgniter CLI seeder、database fixtures、PHP database seed script。

## 授權

CodeIgniter-Seeder 是採用 [MIT 授權](LICENSE) 的開源軟體。
