# Packagist 公開手順

このドキュメントでは、`tarohida/tfa-custom` を Packagist に公開する手順を説明します。

## 事前準備

### 1. 必要なファイルの確認

以下のファイルが揃っていることを確認してください：

- ✅ `composer.json` - Composer パッケージ定義
- ✅ `LICENSE.txt` - MIT ライセンスファイル
- ✅ `README.md` - プロジェクト説明
- ✅ `.gitignore` - Git 除外設定
- ✅ モジュールファイル（`tfa_custom.info.yml`, `tfa_custom.module` など）

### 2. GitHub アカウントの準備

- GitHub アカウントを持っていることを確認
- リポジトリ `tarohida/tfa-custom` が作成済みであること

## 公開手順

### ステップ 1: Git リポジトリの初期化

```bash
cd repo

# Git リポジトリを初期化
git init

# すべてのファイルを追加
git add .

# 初回コミット
git commit -m "Initial commit: TFA Custom module v1.0.0"
```

### ステップ 2: GitHub リモートリポジトリの設定

```bash
# リモートリポジトリを追加
git remote add origin git@github.com:tarohida/tfa-custom.git

# メインブランチを main に設定（推奨）
git branch -M main

# GitHub にプッシュ
git push -u origin main
```

### ステップ 3: バージョンタグの作成

Packagist はバージョン管理に Git タグを使用します。

```bash
# バージョン 1.0.0 のタグを作成
git tag -a 1.0.0 -m "Release version 1.0.0"

# タグを GitHub にプッシュ
git push origin 1.0.0
```

**タグの命名規則:**
- セマンティックバージョニング（SemVer）に従う: `MAJOR.MINOR.PATCH`
- 例: `1.0.0`, `1.1.0`, `2.0.0`
- プレリリース版: `1.0.0-alpha`, `1.0.0-beta`, `1.0.0-rc1`

### ステップ 4: Packagist にパッケージを登録

1. **Packagist にログイン**
   - https://packagist.org/ にアクセス
   - GitHub アカウントでログイン

2. **パッケージを登録**
   - 右上の「Submit」をクリック
   - リポジトリ URL を入力: `https://github.com/tarohida/tfa-custom`
   - 「Check」ボタンをクリック
   - 内容を確認して「Submit」をクリック

3. **登録完了**
   - パッケージページが作成されます: https://packagist.org/packages/tarohida/tfa-custom

### ステップ 5: GitHub Webhook の設定（自動更新）

Packagist を GitHub の更新と自動同期させます。

1. **Packagist で Webhook URL を取得**
   - パッケージページ（https://packagist.org/packages/tarohida/tfa-custom）にアクセス
   - 右側の「Settings」をクリック
   - 「GitHub Service Hook」セクションの URL をコピー

2. **GitHub に Webhook を設定**
   - GitHub リポジトリ（https://github.com/tarohida/tfa-custom）にアクセス
   - 「Settings」→「Webhooks」→「Add webhook」をクリック
   - **Payload URL**: Packagist からコピーした URL を入力
   - **Content type**: `application/json` を選択
   - **Which events**: 「Just the push event」を選択
   - 「Add webhook」をクリック

または、Packagist の自動統合を使用（推奨）:

1. Packagist のパッケージページで「Enable auto-update」をクリック
2. GitHub の権限を許可
3. 自動的に Webhook が設定されます

### ステップ 6: 動作確認

インストールテストを実行：

```bash
# 新しいディレクトリでテスト
mkdir test-install
cd test-install

# Composer でインストール
composer require tarohida/tfa-custom

# インストール成功を確認
ls vendor/tarohida/tfa-custom
```

## バージョン更新手順

新しいバージョンをリリースする場合：

```bash
# 変更をコミット
git add .
git commit -m "Fix: リダイレクト処理の改善"

# GitHub にプッシュ
git push origin main

# 新しいバージョンタグを作成
git tag -a 1.0.1 -m "Release version 1.0.1"

# タグをプッシュ
git push origin 1.0.1
```

Webhook が設定されていれば、Packagist は自動的に更新されます。

## セマンティックバージョニング

バージョン番号の付け方（`MAJOR.MINOR.PATCH`）:

- **MAJOR (1.x.x)**: 互換性のない API 変更
- **MINOR (x.1.x)**: 後方互換性のある機能追加
- **PATCH (x.x.1)**: 後方互換性のあるバグ修正

例:
- `1.0.0` → 初回リリース
- `1.0.1` → バグ修正
- `1.1.0` → 新機能追加
- `2.0.0` → 破壊的変更

## トラブルシューティング

### Packagist がタグを認識しない

```bash
# タグを確認
git tag

# タグを再プッシュ
git push origin --tags

# Packagist で手動更新
# パッケージページの「Update」ボタンをクリック
```

### Composer でインストールできない

1. Packagist でパッケージが公開されているか確認
2. `composer.json` の `name` が正しいか確認（`tarohida/tfa-custom`）
3. Composer のキャッシュをクリア: `composer clear-cache`

### Webhook が動作しない

1. GitHub の Webhooks ページで「Recent Deliveries」を確認
2. エラーがあればログを確認
3. Webhook を削除して再設定

## チェックリスト

公開前に確認：

- [ ] `composer.json` に正しい情報が記載されている
- [ ] `README.md` に使用方法が明記されている
- [ ] `LICENSE.txt` が含まれている
- [ ] Git タグ（例: `1.0.0`）が作成されている
- [ ] GitHub にコードがプッシュされている
- [ ] Packagist にパッケージが登録されている
- [ ] GitHub Webhook が設定されている
- [ ] `composer require tarohida/tfa-custom` でインストールできることを確認

## 参考リンク

- **Packagist**: https://packagist.org/
- **パッケージページ**: https://packagist.org/packages/tarohida/tfa-custom
- **GitHub リポジトリ**: https://github.com/tarohida/tfa-custom
- **Composer ドキュメント**: https://getcomposer.org/doc/
- **セマンティックバージョニング**: https://semver.org/lang/ja/
