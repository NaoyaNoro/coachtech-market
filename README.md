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
5. STRIPEの設定
    環境変数を変更<br>
   ```
STRIPE_KEY=pk_test_51Qm2h2E0GbUpr9cgeUYEaLTesvxA17yk9lkDd0UpxJsdAJp73bzryNHj6vjvr8kLcCPZ1VmrAJYO3IljytbdMbgI00kaLeaofN
STRIPE_SECRET=sk_test_51Qm2h2E0GbUpr9cgIHuUan7UsSwNP77TgtKzTOCjE2hz3VorikQDhGddUsomnzSsQ3rFXNLnrV3DOPQOACc5KjH400YIzTcB1e
   ```
5. アプリケーションキーの作成<br> `php artisan key:generate`
6. マイグレーションの実行<br> `php artisan migrate`
7. シーディングの実行<br> `php artisan db:seed`
8. 保存した画像が正しく表示できない場合は，strageに保存したデータを再登録する<br> `php artisan storage:link`

## 使用技術
* php 7.4.9
* Laravel 8.83.8
* MySQL 8.0.26

## ER図
![er(market)](https://github.com/user-attachments/assets/ee8eeb33-4591-4fe7-90c9-efd4a6e18ef1)


## URL
* 開発環境:http://localhost
* phpmyadmin:http://localhost:8080/
