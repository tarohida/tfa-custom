# TFA Custom

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE.txt)
[![Drupal](https://img.shields.io/badge/drupal-10%20%7C%2011-blue.svg)](https://www.drupal.org)

Drupal の Two-Factor Authentication (TFA) モジュールの動作をカスタマイズするモジュールです。

## 概要

このモジュールは、Drupal の TFA モジュールに以下の機能を追加します：

- **ログイン後のリダイレクト先を自由に設定** - TFA 検証後の遷移先を管理画面から指定可能
- **メール送信の制御** - TFA 有効化/無効化時のメール通知を個別に ON/OFF 可能

## 主な機能

### 1. カスタムリダイレクト

TFA 検証成功後のリダイレクト先を管理画面から設定できます。

- デフォルト: `/` (フロントページ)
- 設定例: `/user`, `/dashboard`, `/admin` など
- パスワードリセット時の動作は元のまま維持

### 2. メール送信制御

TFA の有効化/無効化時に送信される通知メールを個別に制御できます。

- TFA 有効化時のメール送信: ON/OFF 可能
- TFA 無効化時のメール送信: ON/OFF 可能

## 要件

- **Drupal**: 10.x または 11.x
- **PHP**: 8.1 以上
- **依存モジュール**: [TFA (Two-Factor Authentication)](https://www.drupal.org/project/tfa)

## インストール

### Composer 経由（推奨）

```bash
composer require tarohida/tfa-custom
```

モジュールを有効化：

```bash
drush en tfa_custom -y
drush cr
```

### 手動インストール

1. このリポジトリをダウンロード
2. `web/modules/contrib/tfa_custom` または `web/modules/custom/tfa_custom` に配置
3. モジュールを有効化：

```bash
drush en tfa_custom -y
drush cr
```

## 設定方法

1. **管理画面にアクセス**
   - URL: `/admin/config/people/tfa`
   - または: 設定 → ユーザー → TFA

2. **「Custom設定」タブをクリック**

3. **設定項目**

   **ログイン後のリダイレクト先**
   - TFA 検証後の遷移先を指定
   - 例: `<front>`, `/user`, `/dashboard`, `/admin`

   **TFA有効化時にメールを送信**
   - チェックを入れる: メール送信する（デフォルト）
   - チェックを外す: メール送信しない

   **TFA無効化時にメールを送信**
   - チェックを入れる: メール送信する（デフォルト）
   - チェックを外す: メール送信しない

4. **「設定を保存」をクリック**

## 使用例

### 例1: ログイン後にダッシュボードへリダイレクト

```
ログイン後のリダイレクト先: /dashboard
```

### 例2: メール送信を完全に無効化

```
☐ TFA有効化時にメールを送信
☐ TFA無効化時にメールを送信
```

### Drush からの設定

```bash
# リダイレクト先を変更
drush config:set tfa_custom.settings redirect_after_login '/dashboard' -y

# メール送信を無効化
drush config:set tfa_custom.settings send_email_on_enable false -y
drush config:set tfa_custom.settings send_email_on_disable false -y

# キャッシュクリア
drush cr
```

## 技術仕様

### アーキテクチャ

- **リダイレクト制御**: `hook_form_alter()` で TFA エントリーフォームにカスタム submit ハンドラーを追加
- **メール送信制御**: `hook_mail_alter()` でメール送信を動的に抑制
- **設定管理**: Drupal Configuration API を使用（`tfa_custom.settings`）

### ディレクトリ構成

```
tfa_custom/
├── composer.json                 # Composer パッケージ定義
├── LICENSE.txt                   # ライセンスファイル
├── README.md                     # このファイル
├── tfa_custom.info.yml          # モジュール情報
├── tfa_custom.module            # フック実装
├── tfa_custom.routing.yml       # ルート定義
├── tfa_custom.links.task.yml    # タブリンク定義
├── config/
│   ├── install/
│   │   └── tfa_custom.settings.yml    # デフォルト設定
│   └── schema/
│       └── tfa_custom.schema.yml      # 設定スキーマ
└── src/
    └── Form/
        └── TfaCustomSettingsForm.php  # 設定フォーム
```

## トラブルシューティング

### 設定が反映されない

キャッシュをクリアしてください：

```bash
drush cr
```

### 無効なパスを設定した場合

自動的にフロントページにリダイレクトされ、エラーログに記録されます：

```bash
drush watchdog:show --type=tfa_custom
```

## セキュリティ上の注意

メール送信を無効化すると、ユーザーが TFA 設定変更の通知を受け取れなくなります。セキュリティポリシーを考慮の上、慎重に設定してください。

## コントリビューション

バグ報告や機能リクエストは [GitHub Issues](https://github.com/tarohida/tfa-custom/issues) までお願いします。

プルリクエストも歓迎します！

## ライセンス

このプロジェクトは MIT ライセンスの下で公開されています。詳細は [LICENSE.txt](LICENSE.txt) をご覧ください。

## 作者

- **tarohida** - [GitHub](https://github.com/tarohida)

## リンク

- [GitHub リポジトリ](https://github.com/tarohida/tfa-custom)
- [Issue トラッカー](https://github.com/tarohida/tfa-custom/issues)
- [TFA モジュール (Drupal.org)](https://www.drupal.org/project/tfa)
