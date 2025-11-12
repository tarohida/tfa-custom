<?php

namespace Drupal\tfa_custom\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Url;

/**
 * TFA Custom リダイレクト処理の EventSubscriber.
 */
class TfaCustomRedirectSubscriber implements EventSubscriberInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new TfaCustomRedirectSubscriber object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // KernelEvents::RESPONSE を購読（優先度 -10 で後から実行）.
    $events[KernelEvents::RESPONSE][] = ['onResponse', -10];
    return $events;
  }

  /**
   * Response イベントのハンドラ.
   *
   * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
   *   The response event.
   */
  public function onResponse(ResponseEvent $event) {
    $request = $event->getRequest();
    $response = $event->getResponse();

    // check_logged_in パラメータがある場合、TFAログイン完了後.
    if (!$request->query->has('check_logged_in')) {
      return;
    }

    // パスワードリセットトークンがある場合は何もしない.
    if ($request->query->has('pass-reset-token')) {
      return;
    }

    // 設定を取得.
    $config = $this->configFactory->get('tfa_custom.settings');
    $redirect_path = $config->get('redirect_after_login') ?: '<front>';

    // デフォルト設定の場合は何もしない.
    if ($redirect_path === '<front>') {
      return;
    }

    // カスタムリダイレクト先を設定.
    try {
      $url = Url::fromUserInput($redirect_path);
      $redirect_url = $url->toString();

      // 新しいリダイレクトレスポンスを設定.
      $redirect_response = new RedirectResponse($redirect_url);
      $event->setResponse($redirect_response);

      \Drupal::logger('tfa_custom')->info('Redirecting after TFA login to: @path', ['@path' => $redirect_path]);
    }
    catch (\Exception $e) {
      // 無効なパスの場合はログに記録し、元のレスポンスを維持.
      \Drupal::logger('tfa_custom')->error('Invalid redirect path: @path. Error: @error', [
        '@path' => $redirect_path,
        '@error' => $e->getMessage(),
      ]);
    }
  }

}
