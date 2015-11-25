# heroku_php_sample

* 環境  
[Heroku](http://heroku.com/home)
* 言語  
php
* phpパッケージ管理  
[composer](http://getcomposer.org/) ※ 先に入れておく
* フレームワーク  
 [Slim](http://docs.slimframework.com/)
* ストレージ  
ClearDB MySql
* ログ  
papertrail add-on ※ [Heroku上でセットアップ](https://devcenter.heroku.com/articles/getting-started-with-php#provision-add-ons)

参考:[Getting Started with PHP on Heroku](https://devcenter.heroku.com/articles/getting-started-with-php) 

## Deploying

herokuコマンドを使うので先に [Heroku Toolbelt](https://toolbelt.heroku.com/) を入れておき`$ heroku login`する。

### 新しいherokuアプリとして開くとき

リモートリポジトリは以下のようになる
* origin  
git@github.com:uzura/heroku_php_sample.git
* heroku  
https://git.heroku.com/{新しく作られる}.git

※pushはherokuに対して行う

```sh
# ソースをクローン
$ git clone git@github.com:uzura/heroku_php_sample.git
$ cd heroku_php_sample

# herokuに登録
$ heroku create
$ git push heroku master

# heroku設定
$ heroku addons:create papertrail
$ heroku addons:create cleardb
$ heroku config:set IS_HEROKU=true
# 手元で動くように環境変数を.envにコピー
$ heroku config:get CLEARDB_DATABASE_URL -s  >> .env

# 手元で確認するためビルトインサーバーを起動するとき
# http://localhost:8000/hello/test で開く
$ composer install
$ php -S localhost:8000 -t ./web

# heroku上で動いているものを開くとき
$ heroku open
```

### 既存herokuアプリ([aqueous-mountain-1793](https://dashboard.heroku.com/apps/aqueous-mountain-1793))として開くとき

リモートリポジトリは以下のようになる
* origin  
git@github.com:uzura/heroku_php_sample.git
* heroku  
https://git.heroku.com/aqueous-mountain-1793.git

※originにpushするとherokuにデプロイされる設定なのでpushはoriginに対して行う。

```sh
# ソースをクローン
$ git clone git@github.com:uzura/heroku_php_sample.git
$ cd heroku_php_sample
# herokuに紐付け
$ heroku git:remote --app aqueous-mountain-1793

# 手元で動くように環境変数を.envにコピー
$ heroku config:get CLEARDB_DATABASE_URL -s  >> .env

# 手元で確認するためビルトインサーバーを起動するとき
# http://localhost:8000/hello/test で開く
$ composer install
$ php -S localhost:8000 -t ./web

# heroku上で動いているものを開くとき
$ heroku open
```

## ログ確認
* Chrome Loggerで確認する場合  
ChromeにChrome Loggerを入れてアイコンクリックでONにする。
デベロッパーツールのconsoleを開く

* 標準エラー出力/標準出力で確認  
heroku上で動いている場合。ビルトインサーバーの場合は何もしなくて良い。
```sh
$ heroku logs -t
```

* papertrailで確認する  
```sh
$ heroku addons:open papertrail
```

## Documentation

For more information about using PHP on Heroku, see these Dev Center articles:

- [PHP on Heroku](https://devcenter.heroku.com/categories/php)
