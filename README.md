# coachtechフリマ

## Dockerビルド
1. リポジトリの複製<br>`git clone git@github.com:NaoyaNoro/mogitate.git`
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
1. 環境変数の設定
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


   

## 使用技術
* php 7.4.9
* Laravel 8.83.8
* MySQL 8.0.26

## ER図
![er(market)](https://github.com/user-attachments/assets/ee8eeb33-4591-4fe7-90c9-efd4a6e18ef1)


## URL
* 開発環境:http://localhost
* phpmyadmin:http://localhost:8080/
