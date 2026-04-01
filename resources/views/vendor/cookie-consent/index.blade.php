@if($cookieConsentConfig['enabled'] && ! $alreadyConsentedWithCookies)

    @include('cookie-consent::dialogContents')

    <script>
        window.laravelCookieConsent = (function () {
            const COOKIE_VALUE = 1;
            const COOKIE_DOMAIN = '{{ config('session.domain') ?? request()->getHost() }}';
            const COOKIE_NAME = '{{ $cookieConsentConfig['cookie_name'] }}';

            function consentWithCookies(preferences) {
                const cookieValue = preferences ? JSON.stringify(preferences) : COOKIE_VALUE;
                setCookie(COOKIE_NAME, cookieValue, {{ $cookieConsentConfig['cookie_lifetime'] }});
                hideCookieDialog();
            }

            function cookieExists(name) {
                return document.cookie.split('; ').some(function(item) {
                    return item.indexOf(name + '=') === 0;
                });
            }

            function hideCookieDialog() {
                var dialogs = document.getElementsByClassName('js-cookie-consent');
                for (var i = 0; i < dialogs.length; ++i) {
                    dialogs[i].style.display = 'none';
                }
            }

            function showModal() {
                var modal = document.getElementById('cookie-preferences-modal');
                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
            }

            function hideModal() {
                var modal = document.getElementById('cookie-preferences-modal');
                if (modal) {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            }

            function setCookie(name, value, expirationInDays) {
                var date = new Date();
                date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000));
                document.cookie = name + '=' + value
                    + ';expires=' + date.toUTCString()
                    + ';domain=' + COOKIE_DOMAIN
                    + ';path=/{{ config('session.secure') ? ';secure' : null }}'
                    + '{{ config('session.same_site') ? ';samesite='.config('session.same_site') : null }}';
            }

            function getCookie(name) {
                var nameEQ = name + '=';
                var ca = document.cookie.split(';');
                for (var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
                }
                return null;
            }

            function hasConsented() {
                return cookieExists(COOKIE_NAME);
            }

            function getPreferences() {
                var cookie = getCookie(COOKIE_NAME);
                if (!cookie) return null;
                if (cookie === '1') return { analytics: true, marketing: true };
                try { return JSON.parse(cookie); } catch (e) { return null; }
            }

            function hasCategory(category) {
                var prefs = getPreferences();
                if (!prefs) return false;
                return prefs[category] === true;
            }

            if (cookieExists(COOKIE_NAME)) {
                hideCookieDialog();
            }

            var agreeButtons = document.getElementsByClassName('js-cookie-consent-agree');
            for (var i = 0; i < agreeButtons.length; ++i) {
                agreeButtons[i].addEventListener('click', function() {
                    consentWithCookies({ analytics: true, marketing: true });
                });
            }

            var rejectButtons = document.getElementsByClassName('js-cookie-consent-reject');
            for (var i = 0; i < rejectButtons.length; ++i) {
                rejectButtons[i].addEventListener('click', function() {
                    consentWithCookies({ analytics: false, marketing: false });
                });
            }

            var customizeButtons = document.querySelectorAll('[data-cookie-consent-customize]');
            customizeButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var prefs = getPreferences();
                    var analyticsToggle = document.getElementById('cookie-analytics');
                    var marketingToggle = document.getElementById('cookie-marketing');
                    if (analyticsToggle) analyticsToggle.checked = prefs ? prefs.analytics : false;
                    if (marketingToggle) marketingToggle.checked = prefs ? prefs.marketing : false;
                    showModal();
                });
            });

            var saveButtons = document.querySelectorAll('[data-cookie-consent-save]');
            saveButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var analytics = document.getElementById('cookie-analytics');
                    var marketing = document.getElementById('cookie-marketing');
                    consentWithCookies({
                        analytics: analytics ? analytics.checked : false,
                        marketing: marketing ? marketing.checked : false
                    });
                    hideModal();
                });
            });

            var closeButtons = document.querySelectorAll('[data-cookie-consent-close], [data-cookie-consent-cancel]');
            closeButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    hideModal();
                });
            });

            var backdrops = document.querySelectorAll('[data-cookie-consent-backdrop]');
            backdrops.forEach(function(backdrop) {
                backdrop.addEventListener('click', function() {
                    hideModal();
                });
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') hideModal();
            });

            return {
                consentWithCookies: consentWithCookies,
                hideCookieDialog: hideCookieDialog,
                hasConsented: hasConsented,
                getPreferences: getPreferences,
                hasCategory: hasCategory,
                showModal: showModal,
                hideModal: hideModal
            };
        })();
    </script>

@endif
