<div class="social-share">
    @if (config('app.share_button_email'))
    <a href="mailto:?subject={{ urlencode(__('SHARE_EMAIL_SUBJECT')) }}&body={{ urlencode(__('SHARE_EMAIL_BODY')) }}%20" title="@lang('E-mail Share')" target="_blank" class="btn btn-email">
        <img src="/img/icons/mail-x26.png" alt="">
    </a>
    @endif

    @if (config('app.share_button_facebook'))
    <a href="https://www.facebook.com/sharer/sharer.php?u=" title="@lang('Facebook Share')" target="_blank" class="btn btn-facebook">
        <img src="/img/icons/facebook.png" alt="">
    </a>
    @endif

    @if (config('app.share_button_twitter'))
    <a href="https://twitter.com/intent/tweet?url=" title="@lang('Twitter Share')" target="_blank" class="btn btn-twitter">
        <img src="/img/icons/twitter.png" alt="">
    </a>
    @endif

    @if (config('app.share_button_whatsapp'))
    <!-- https://web.whatsapp.com/send?text=message -->
    <a href="https://wa.me/?text={{ urlencode(__('SHARE_WHATSAPP_MESSAGE')) }}%20" title="@lang('Whatsapp Share')" target="_blank" class="btn btn-whatsapp">
        <img src="/img/icons/whatsapp.png" alt="">
    </a>
    @endif

    @if (config('app.share_button_vkontakte'))
    <a href="https://vk.com/share.php?url=" title="@lang('VKontakte Share')" target="_blank" class="btn btn-vkontakte">
        <img src="/img/icons/vk.png" alt="">
    </a>
    @endif
</div>
