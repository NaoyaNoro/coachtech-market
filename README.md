# coachtechフリマ
## プロジェクトの概要
アイテムの出品と購入を行うためのフリマアプリを開発する

## Dockerビルド
1. リポジトリの複製
   ```
   git clone git@github.com:NaoyaNoro/coachtech-market.git
   ```
3. DockerDesktopアプリを立ち上げる
4. dockerをビルドする<br>
   ```
   docker-compose up -d --build
   ```
>3を実行するときに，`no matching manifest for linux/arm64/v8 in the manifest list entries` というようなエラーが出ることがあります。この場合，docker-compose.ymlのmysqlサービスとphp myadminのサービスの箇所に `platform: linux/amd64` というような表記を追加してください。

## Laravel環境構築
1. PHPコンテナ内にログインする
   ```
   docker-compose exec php bash
   ```
2. composerコマンドを使って必要なコマンドのインストール
   ```
   composer install
   ``` 
4. .env.exampleファイルから.envを作成
   ```
   cp .env.example .env
   ```
6. 環境変数を変更<br>
   ```
   DB_HOST=mysql
   DB_PORT=3306 
   DB_DATABASE=laravel_db
   DB_USERNAME=laravel_user
   DB_PASSWORD=laravel_pass
   ```
7. アプリケーションキーの作成
   ```
   php artisan key:generate
   ```
8. キャッシュのクリア
   ```
   php artisan config:clear
   php artisan cache:clear
   php artisan config:cache
   ```
9. マイグレーションの実行<br>
    ```
    php artisan migrate
    ```
10. シーディングの実行<br>
    ```
    php artisan db:seed
    ```
11. 保存した画像が正しく表示できない場合は，strageに保存したデータを再登録する<br>
    ```
    php artisan storage:link
    ```

## Pro試験についての諸注意
* 3人(ユーザー1，ユーザー2，ユーザー3)のユーザーに関するダミーデータを作成しています。ユーザー1は商品番号CO01〜CO05の商品，ユーザー2は商品番号CO06〜CO10の商品が出品者として紐づけられています。ユーザー3については，どの商品も出品者としては，紐づけられていません。さらに取引のダミーデータも作成しております。商品番号CO01の商品について，ユーザー1とユーザー2の間で取引が行われて，会話しているデータが入っております。
* ある一つの商品について，二人のユーザーから同時に取引を受けると，出品者の取引中の商品には，同じ商品が表示されて非常にわかりにくくなってしまいます。そこで，あるユーザーが取引中の商品については，他のユーザーが取引ができない仕様にしました。取引が完了すると，他のユーザーも取引を開始できます。
* 要件定義書を読むと，どのように商品の取引を開始するかが明記されていませんでした。そこで商品詳細ページに「取引へ」という青色のボタンを作成し，そこを押すことで取引チャット画面に遷移して取引を開始できる仕様にしています。
* 取引チャット画面で一番下部の「チャット送信画面」「画像追加ボタン」「送信ボタン」と横並びになっています。ただ，送信する写真の確認画面を作成した方が良いと考えて、ここの部分は2段構成にいたしました。Figmaと違う形になっています。
* 評価の送信メールについては，MailHog(MailHog:http://localhost:8025/)で実装いたしました。評価された方が，メールを受け取る仕様になっております。

## Stripeの設定
1. APIキーを取得する<br>
   i. [Stripe公式サイト](https://dashboard.stripe.com/register)でアカウントを作成<br>
   ii.「開発者」 → 「APIキー」から `公開可能キー` (`STRIPE_KEY`) と `シークレットキー` (`STRIPE_SECRET`) をコピー
2. 取得したSTRIPEのAPIキーを`.env`に追加<br>
   ```
   STRIPE_KEY=pk_test_xxxxxxxxxxxxxxxxxxxxxxxxx
   STRIPE_SECRET=sk_test_xxxxxxxxxxxxxxxxxxxxxxxxx
   ```
3. `config/services.php`にStripeの設定を追加(今回は記載済み)
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
7. 商品購入画面にて，支払い方法を選択後，`購入する`ボタンを押す
8. Stripeのテストカードで支払い
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
2. env.の環境変数を修正
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
5. 会員登録後，`認証はこちらから`というボタンを押すと，MailHogのページに遷移するので，そこで`Verify Email Address`をクリックする
6. ページ遷移後`Verify Email Address`というボタンを押すと，メール認証が行われて，プロフィール設定画面に遷移する
## 単体テストの設定
1. MySQLコンテナ内にログインする
   ```
   docker-compose exec mysql bash
   ```
3. rootユーザーでログインする。(PW:root)
   ```
   mysql -u root -p
   ```
5. demo_testデータベースの新規作成を行う。
   ```
   CREATE DATABASE demo_test;
   ```
6. rootとlaravel_userにdemo_testへの権限を与える
   ```
   GRANT ALL PRIVILEGES ON demo_test.* TO 'root'@'%';
   GRANT ALL PRIVILEGES ON demo_test.* TO 'laravel_user'@'%';
   FLUSH PRIVILEGES;
   ```
7. configディレクトリ内のdatabases.phpのconnectionsに以下を追加(今回は記載済み)<br>
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
8. PHPコンテナ内にログインする 
   ```
   docker-compose exec php bash
   ```
9. .envファイルから.env.testingを作成
   ```
   cp .env .env.testing
   ```
10. .env.testingを以下のように設定(KEYの設定は空にしておく)
    ```
    APP_ENV=testing
    APP_KEY=
    DB_CONNECTION=mysql_test
    DB_DATABASE=demo_test
    DB_USERNAME=root
    DB_PASSWORD=root
    ```
11. テスト用のアプリケーションキーの作成
    ```
    php artisan key:generate --env=testing
    ```
12. テスト環境への切り替え
    ```
    export $(grep -v '^#' .env.testing | xargs)
    ```
13. キャッシュのクリア
    ```
    php artisan config:clear
    php artisan cache:clear
    ```
14. phpunit.xmlのphp箇所に以下を追加(今回は記載済み)
    ```
    <env name="APP_ENV" value="testing"/>
    <env name="DB_CONNECTION" value="mysql_test"/>
    <env name="DB_DATABASE" value="demo_test"/>
    <env name="SESSION_DRIVER" value="array"/>
    ```
15. テスト用データベースdemo_testのマイグレーション
    ```
    php artisan migrate --env=testing
    ```
> 9で`php artisan key:generate --env=testing`を実行してもアプリケーションキーがうまく作成できないときがあります。その場合は，`php artisan key:generate --show`で手動でアプリケーションキーを作成して，`APP_KEY=`の後に表記してください。

## 単体テストの実施
1. テスト項目一覧

| テスト項目 | テストファイル名| 実行コマンド
|----------|----------|----------|
| 会員登録機能  | RegisterTest  | `php artisan test --filter RegisterTest`|
| ログイン機能  | LoginTest  |`php artisan test --filter LoginTest` |
| ログアウト機能  | LogoutTest  | `php artisan test --filter LogoutTest`　|
| 商品一覧取得  | IndexTest  |`php artisan test --filter IndexTest` |
| マイリスト一覧取得  | MyListTest  | `php artisan test --filter MyListTest`|
| 商品検索機能  | SearchTest  | `php artisan test --filter SearchTest`|
| 商品詳細情報取得  | DetailTest  | `php artisan test --filter DetailTest`|
| いいね機能  | GoodTest  | `php artisan test --filter GoodTest`|
| コメント送信機能  | CommentTest  | `php artisan test --filter CommentTest`|
| 商品購入機能  | PurchaseTest  |`php artisan test --filter PurchaseTest` |
| 支払い方法選択機能  | PurchaseMethodTest(Duskを使用)  | 下記参照 |
| 配送先変更機能  | AddressTest  | `php artisan test --filter AddressTest`|
| ユーザー情報取得  | MypageTest  | `php artisan test --filter MypageTest`|
| ユーザー情報変更  | ChangeProfileTest  | `php artisan test --filter ChangeProfileTest`|
| 出品商品情報登録  | SellTest  | `php artisan test --filter SellTest`|

2. 各項目のテストを実施<br>
   <例>会員登録機能をテストするとき
   ```
   php artisan test --filter RegisterTest
   ```
   <例>同時にテストするとき<br>
   ```
   php artisan test
   ```
3. テスト終了後，本番環境への切り替え
   ```
   export $(grep -v '^#' .env | xargs)
   ```
4. キャッシュのクリア
    ```
    php artisan config:clear
    php artisan cache:clear
    ```
> * 商品詳細情報取得では「必要な情報が表示される」「複数選択されたカテゴリーが表示されているか」の２項目あります。これらはDetailTest内でのtest_detail_productという関数で一度にテストしています。
> * 商品購入機能では「「購入する」ボタンを押下すると購入が完了する」「購入した商品は商品一覧画面にて「sold」と表示される」「「プロフィール/購入した商品一覧」に追加されている」の3項目あります。これらはPurchaseTest内でのtest_purchase_stripe_paymentという関数で一度にテストしています。
   
## DUSKの設定
> [!NOTE]
> Laravel Duskは，ブラウザテストを自動化するためのツールである。支払い方法選択機能では，JavaScriptを使うことで支払い方法が即座に小計画面に反映されるようになっている。Laravelの通常の単体テストでは，バックエンドのロジックを検証できるが，JavaScriptを含む動作は確認できない。そこでDuskを使うことで，実際のブラウザを起動して，JavaScript を含むフロントエンドの動作をテストできる。

1. PHPコンテナ内にログインする
   ```
   docker-compose exec php bash
   ```
2. Duskのインストール
   ```
   composer require --dev laravel/dusk
   php artisan dusk:install
   ```
3. .envファイルから.env.dusk.localを作成
   ```
   cp .env .env.dusk.local
   ```
4. .env.dusk.localを以下のように設定(KEYの設定は空にしておく)
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
6. テスト環境への切り替え
   ```
   export $(grep -v '^#' .env.testing | xargs)
   ```
7. テスト用のアプリケーションキーの作成
   ```
   php artisan key:generate --env=dusk
   ```
8. コンテナを出る
   ```
   exit
   ```
9. dockerを一度停止する
   ```
   docker-compose down
   ```
10. 再度dockerをビルドする
    ```
    docker-compose up -d --build
    ```
11. PHPコンテナ内にログインする 
    ```
    docker-compose exec php bash
    ```
12. キャッシュのクリア
    ```
    php artisan config:clear
    php artisan cache:clear
    ```
13. 支払い方法選択機能のテストを行う
    ```
    php artisan dusk --filter=PurchaseMethodTest
    ```
14. テスト終了後，本番環境への切り替え
    ```
    export $(grep -v '^#' .env | xargs)
    ```
15. キャッシュのクリア
    ```
    php artisan config:clear
    php artisan cache:clear
    ```
>　7で`php artisan key:generate --env=dusk`を実行してもアプリケーションキーがうまく作成できないときがあります。その場合は，`php artisan key:generate --show`で手動でアプリケーションキーを作成して，`APP_KEY=`の後に表記してください。

## 諸注意
* 基本設計書(生徒様入力用)のバリデーションのところで，運営様と相談の上，変更しています。AddressRequest.phpとProfileRequest.phpが分離されており，意図がわからない仕様になっておりました。コーチとも相談の上，AddressRequest.php一つに統合しています。つまりプロフィール画像に関するバリデーションもAddressRequest.phpにまとめてあります。
* AddressReauest.phpのプロフィール画像についてですが，コーチと相談して「拡張子が.jpegもしくは.png」に付け足して，「入力必須(初回登録時)」というバリデーションを加えています。プロフィール変更する場合は，初回登録時の画像がデフォルトの値としてそのまま適用されます。
* 住所変更画面では，デフォルトではプロフィールの登録された「郵便番号」，「住所」，「建物名」が表示されるようになっております。これを消して更新するとエラーになってしまいますので住所変更のページにはChangeAddressRequest.phpというリクエストを加えています。
* PurchaseRequest.phpでは「配送先」に「選択必須」という項目がありましたが，これはChangeAddressRequest.phpを作成したことにより，空であることはないのでつけていません。
* ExhibitationRequest.phpでは，「商品のブランド名」はバリデーションの対象外でした。しかしテストケース一覧では，ブランド名の表示を要求されているので，「入力必須」でバリデーション項目に加えています。
* Figmaでは商品詳細画面に「カラー」という項目がありましたが，商品出品画面には「カラー」項目はありませんでした。FIgmaの商品詳細画面に合わせて，商品出品画面の項目に「カラー」を付け加えてあります。
* 機能要件のFN005とFN011では会員登録後，ログイン画面に遷移するように指定があります。しかしFigmaを確認すると，会員登録→メール認証→プロフィール設定画面→トップ画面という遷移になっていましたので，Figmaに従って作成しています。

## 使用技術
* php 7.4.9
* Laravel 8.83.8
* MySQL 8.0.26
* Stripe  v16.6.0
* MailHog 1.0.1
* Dusk v6.25.2

## ER図
<img width="1722" height="1472" alt="er(market)" src="https://github.com/user-attachments/assets/23716e94-1c25-469c-af69-b522d0e28e23" />




## URL
* 開発環境:http://localhost
* phpmyadmin:http://localhost:8080/
* MailHog:http://localhost:8025/

# new-market
