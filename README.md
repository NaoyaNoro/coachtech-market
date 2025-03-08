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
6. マイグレーションの実行<br> `php artisan migrate`
7. シーディングの実行<br> `php artisan db:seed`
8. 保存した画像が正しく表示できない場合は，strageに保存したデータを再登録する<br> `php artisan storage:link`

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
4. Stripeのテストカードで支払い
   ```
   カード番号: 4242 4242 4242 4242
   有効期限: 任意の未来日（例: 12/34）
   CVC: 123
   ```
## MailHogの設定
1. MailHogのインストール
   ```
   docker run --name mailhog -d --platform linux/amd64 -p 1025:1025 -p 8025:8025  
   mailhog/mailhog
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
3.PHPコンテナ内にログインする <br>`docker-compose exec php bash`
4.キャッシュのクリア
   ```
   php artisan config:clear
   php artisan cache:clear
   php artisan config:cache
   ```
## 単体テストの設定
1. MySQLコンテナ内にログインする <br>`docker-compose exec mysql bash`
2. rootユーザーでログインする。<br>`mysql -u root -p`
3. demo_testデータベースの新規作成を行う。
   ```
   > CREATE DATABASE demo_test;
   > SHOW DATABASES;
   ```
4. rootにdemo_testへの権限を与える
   ```
   GRANT ALL PRIVILEGES ON demo_test.* TO 'laravel_user'@'%';
   FLUSH PRIVILEGES;
   ```
5. databases.phpのconnectionsに以下を追加<br>
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
7. .env.testingを以下のように設定
   ```
   APP_ENV=testing
   DB_CONNECTION=mysql
   DB_DATABASE=demo_test
   DB_USERNAME=root
   DB_PASSWORD=root
   ```
8. 設定キャッシュのクリアと再生成
   ```
   php artisan config:clear
   php artisan config:cache
   ```
9. phpunit.xmlのphp箇所に以下を追加<br>
　　
      ```
      <env name="APP_ENV" value="testing"/>
      <env name="DB_CONNECTION" value="mysql_test"/>
      <env name="DB_DATABASE" value="demo_test"/>
      <env name="SESSION_DRIVER" value="array"/>
      ```
10. テスト用データベースdemo_testのマイグレーション <br>`php artisan migrate --env=testing
`


　　






   


   

## 使用技術
* php 7.4.9
* Laravel 8.83.8
* MySQL 8.0.26

## ER図
![er(market)](https://github.com/user-attachments/assets/ee8eeb33-4591-4fe7-90c9-efd4a6e18ef1)


## URL
* 開発環境:http://localhost
* phpmyadmin:http://localhost:8080/
