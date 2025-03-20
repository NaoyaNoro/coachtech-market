# coachtechフリマ

## Dockerビルド
1. リポジトリの複製<br>`git clone git@github.com:NaoyaNoro/coachtech-market.git`
2. DockerDesktopアプリを立ち上げる
3. dockerをビルドする<br>`docker-compose up -d --build`
>3を実行するときに，`no matching manifest for linux/arm64/v8 in the manifest list entries` というようなエラーが出ることがあります。この場合，docker-compose.ymlのmysqlサービスとphp myadminのサービスの箇所に `platform: linux/amd64` というような表記を追加してください

## Laravel環境構築
1. PHPコンテナ内にログインする <br>`docker-compose exec php bash`
2. composerコマンドを使って必要なコマンドのインストール <br>`composer install`
3. .env.exampleファイルから.envを作成 <br>`cp .env.example .env`
4. 環境変数を変更<br>
   ```
   DB_HOST=mysql
   DB_PORT=3306 
   DB_DATABASE=laravel_db
   DB_USERNAME=laravel_user
   DB_PASSWORD=laravel_pass
   ```
5. アプリケーションキーの作成<br> `php artisan key:generate`
6. キャッシュのクリア
   ```
   php artisan config:clear
   php artisan cache:clear
   php artisan config:cache
   ```
7. マイグレーションの実行<br> `php artisan migrate`
8. シーディングの実行<br> `php artisan db:seed`
9. 保存した画像が正しく表示できない場合は，strageに保存したデータを再登録する<br> `php artisan storage:link`

## Stripeの設定
1. APIキーを取得する<br>
   i. [Stripe公式サイト](https://dashboard.stripe.com/register)でアカウントを作成<br>
   ii.「開発者」 → 「APIキー」から `公開可能キー` (`STRIPE_KEY`) と `シークレットキー` (`STRIPE_SECRET`) をコピー
2. 取得したSTRIPEのAPIキーを`.env`に追加<br>
   ```
   STRIPE_KEY=pk_test_xxxxxxxxxxxxxxxxxxxxxxxxx
   STRIPE_SECRET=sk_test_xxxxxxxxxxxxxxxxxxxxxxxxx
   ```
3. `config/services.php`にStripeの設定を追加
   ```
   'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    ],
   ```
4. PHPコンテナ内にログインする 
   ```
   docker-compose exec php bash
   ```
5. キャッシュのクリア
   ```
   php artisan config:clear
   php artisan cache:clear
   php artisan config:cache
   ```
6. Stripeのライブラリをインストール
   ```
   composer require stripe/stripe-php

   ```
7. Stripeのテストカードで支払い
   ```
   カード番号: 4242 4242 4242 4242
   有効期限: 任意の未来日（例: 12/34）
   CVC: 123
   ```
>今回はStripeのテスト決済機能を用いています。テスト決済では，即時決済ができるという観点から「カード決済」のみが適用できます。「コンビニ支払い」は即時決済ができないので購入手続きが完了しないことをご了承ください。
## MailHogの設定
1. MailHogのインストール
   ```
   docker run --name mailhog -d --platform linux/amd64 -p 1025:1025 -p 8025:8025 mailhog/mailhog
   ```
2. env.に環境変数の追加
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=host.docker.internal
   MAIL_PORT=1025
   MAIL_USERNAME=""
   MAIL_PASSWORD=""
   MAIL_ENCRYPTION=null
   MAIL_FROM_ADDRESS=no-reply@example.com
   MAIL_FROM_NAME="${APP_NAME}"
   ```
3. PHPコンテナ内にログインする 
   ```
   docker-compose exec php bash
   ```
4. キャッシュのクリア
   ```
   php artisan config:clear
   php artisan cache:clear
   php artisan config:cache
   ```
5. `認証はこちらから`というボタンを押すと，MailHogのページに遷移するので，そこで`Verify Email Address`をクリックする
6. ページ遷移後`Verify Email Address`というボタンを押すと，メール認証が行われて，プロフィール設定画面に遷移する
## 単体テストの設定
1. MySQLコンテナ内にログインする <br>`docker-compose exec mysql bash`
2. rootユーザーでログインする。<br>`mysql -u root -p`
3. demo_testデータベースの新規作成を行う。
   ```
   > CREATE DATABASE demo_test;
   > SHOW DATABASES;
   ```
4. rootとlaravel_userにdemo_testへの権限を与える
   ```
   GRANT ALL PRIVILEGES ON demo_test.* TO 'root'@'%';
   GRANT ALL PRIVILEGES ON demo_test.* TO 'laravel_user'@'%';
   FLUSH PRIVILEGES;
   ```
5. configディレクトリ内のdatabases.phpのconnectionsに以下を追加<br>
   ```
   'mysql_test' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => 'demo_test',
            'username' => 'root',
            'password' => 'root',
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
             PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],
    ```
6. .envファイルから.env.testingを作成<br>`cp .env .env.testing`
7. .env.testingを以下のように設定(KEYの設定は空にしておく)
   ```
   APP_ENV=testing
   APP_KEY=
   DB_CONNECTION=mysql_test
   DB_DATABASE=demo_test
   DB_USERNAME=root
   DB_PASSWORD=root
   ```
8. テスト用のアプリケーションキーの作成<br> `php artisan key:generate --env=testing`
9. テスト環境への切り替え<br>`export $(grep -v '^#' .env.testing | xargs)`
10. 設定キャッシュのクリア
    ```
    php artisan config:clear
    php artisan cache:clear
    ```
11. phpunit.xmlのphp箇所に以下を追加
    ```
    <env name="APP_ENV" value="testing"/>
    <env name="DB_CONNECTION" value="mysql_test"/>
    <env name="DB_DATABASE" value="demo_test"/>
    <env name="SESSION_DRIVER" value="array"/>
    ```
13. テスト用データベースdemo_testのマイグレーション <br>`php artisan migrate --env=testing
`
>`php artisan key:generate --env=testing`を実行してもアプリケーションキーがうまく実行できないときがあります。その場合は，`php artisan key:generate --show`で手動でアプリケーションキーを作成して，`APP_KEY=`の後に表記してください。

## 単体テストの実施
1. テスト項目一覧

| テスト項目 | テストファイル名| 
|----------|----------|
| 会員登録機能  | RegisterTest  | OK
| ログイン機能  | LoginTest  | OK
| ログアウト機能  | LogoutTest  | 　OK
| 商品一覧取得  | IndexTest  | OK
| マイリスト一覧取得  | MyListTest  | OK
| 商品検索機能  | SearchTest  | OK
| 商品詳細情報取得  | DetailTest  | OK
| いいね機能  | GoodTest  | OK
| コメント送信機能  | CommentTest  | OK
| 商品購入機能  | PurchaseTest  | OK
| 支払い方法選択機能  | PurchaseMethodTest(Duskを使用)  |
| 配送先変更機能  | AddressTest  | OK
| ユーザー情報取得  | MypageTest  | OK
| ユーザー情報変更  | ChangeProfileTest  | OK
| 出品商品情報登録  | SellTest  | OK

2. 各項目のテストを実施
　<例>会員登録機能をテストするときは，<br>`php artisan test --filter RegisterTest`
3. テスト終了後，本番環境への切り替え<br>`export $(grep -v '^#' .env | xargs)`
4. 設定キャッシュのクリア
    ```
    php artisan config:clear
    php artisan cache:clear
    ```
   
## DUSKの設定
> [!NOTE]
> Laravel Duskは，ブラウザテストを自動化するためのツールである。支払い方法選択機能では，JavaScriptを使うことで支払い方法が即座に小計画面に反映されるようになっている。Laravelの通常の単体テストでは，バックエンドのロジックを検証できるが，JavaScriptを含む動作は確認できない。そこでDuskを使うことで，実際のブラウザを起動して，JavaScript を含むフロントエンドの動作をテストできる。

1. PHPコンテナ内にログインする <br>`docker-compose exec php bash`
2. Duskのインストール
   ```
   composer require --dev laravel/dusk
   php artisan dusk:install
   ```
3. .envファイルから.env.dusk.localを作成 <br>`cp .env .env.dusk.local`
4. .env.dusk.localを以下のように設定(KEYの設定はからにしておく)
   ```
   APP_ENV=dusk.local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://nginx

   DB_CONNECTION=mysql_test
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=demo_test
   DB_USERNAME=laravel_user
   DB_PASSWORD=laravel_pass
   ```
5. testディレクトリ内のDuskTestCase.phpを以下のように修正する
   ```
   protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless', // GUI なしのヘッドレスモード
            '--no-sandbox',
            '--disable-dev-shm-usage',
            '--window-size=1920,1080',
        ]);

        return RemoteWebDriver::create(
            'http://selenium:4444/wd/hub', // Selenium サーバー
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options
            )
        );
    }
   ```
6. テスト環境への切り替え<br>`export $(grep -v '^#' .env.testing | xargs)`
7. テスト用のアプリケーションキーの作成<br> `php artisan key:generate --env=dusk`
8. dockerを一度停止する<br>`docker-compose down`
9. 再度dockerをビルドする<br>`docker-compose up -d --build`
10. 設定キャッシュのクリア
    ```
    php artisan config:clear
    php artisan cache:clear
    ```
11. 支払い方法選択機能のテストを行う<br>`php artisan dusk --filter=PurchaseMethodTest`
12. テスト終了後，本番環境への切り替え<br>`export $(grep -v '^#' .env | xargs)`
13. 設定キャッシュのクリア
    ```
    php artisan config:clear
    php artisan cache:clear
    ```
>`php artisan key:generate --env=dusk`を実行してもアプリケーションキーがうまく実行できないときがあります。その場合は，`php artisan key:generate --show`で手動でアプリケーションキーを作成して，`APP_KEY=`の後に表記してください。

## 諸注意
* 基本設計書(生徒様入力用)のバリデーションのところで，運営様と相談の上，変更しています。AddressRequest.phpとProfileRequest.phpが分離されており，意図がわからない仕様になっておりました。コーチとも相談の上，AddressRequest.php一つに統合しています。つまりプロフィール画像に関するバリデーションもAddressRequest.phpにまとめてあります。
* AddressReauest.phpのプロフィール画像についてですが，コーチと相談して「拡張子が.jpegもしくは.png」に付け足して，「入力必須」というバリデーションを加えています。
* 住所変更画面では，デフォルトではプロフィールの登録された「郵便番号」，「住所」，「建物名」が表示されるようになっております。これを消して更新するとエラーになってしまいますので住所変更のページにはChangeAddress.phpというリクエストを加えています。

## 使用技術
* php 7.4.9
* Laravel 8.83.8
* MySQL 8.0.26

## ER図
![er(coachtech_msrket)](https://github.com/user-attachments/assets/4727a7d6-7eef-4b2e-9360-6de55950bcd6)



## URL
* 開発環境:http://localhost
* phpmyadmin:http://localhost:8080/

