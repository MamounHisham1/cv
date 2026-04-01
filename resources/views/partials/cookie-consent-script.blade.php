<script>
    (function() {
        function getCookie(name) {
            var nameEQ = name + '=';
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
            }
            return null;
        }

        function hasConsent(category) {
            var cookie = getCookie('{{ config('cookie-consent.cookie_name') }}');
            if (!cookie) return false;
            if (cookie === '1') return true;
            try {
                var prefs = JSON.parse(cookie);
                return prefs[category] === true;
            } catch (e) {
                return false;
            }
        }

        window.cookieConsent = {
            hasConsent: hasConsent,
            loadScript: function(src, callback) {
                if (!hasConsent('analytics') && !hasConsent('marketing')) return;
                var script = document.createElement('script');
                script.src = src;
                script.async = true;
                if (callback) script.onload = callback;
                document.head.appendChild(script);
            },
            loadAnalytics: function() {
                if (hasConsent('analytics')) {
                    window.dataLayer = window.dataLayer || [];
                    function gtag(){dataLayer.push(arguments);}
                    gtag('js', new Date());
                    gtag('config', '{{ config('services.analytics_id', '') }}');
                    var script = document.createElement('script');
                    script.src = 'https://www.googletagmanager.com/gtag/js?id={{ config('services.analytics_id', '') }}';
                    script.async = true;
                    document.head.appendChild(script);
                }
            },
            init: function() {
                this.loadAnalytics();
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            window.cookieConsent.init();
        });

        document.addEventListener('cookie-consent-updated', function(e) {
            if (e.detail && e.detail.analytics) {
                window.cookieConsent.loadAnalytics();
            }
        });
    })();
</script>
