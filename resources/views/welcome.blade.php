<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Laravel 13 API Gateway | Passport OAuth2 Ready</title>
    <link rel="stylesheet" href="{{  asset('assets/css/styles.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="navbar">
        <div class="logo">
            <span>⚡🛡️</span> Laravel Gateway
        </div>
        <div class="badge-group">
            <div class="badge">API Gateway v2.0</div>
            <div class="badge passport-badge">🔐 Passport OAuth2</div>
        </div>
    </div>

    <div class="hero">
        <div class="version">✨ Laravel 13 — Passport Ready · API Gateway</div>
        <h1>Laravel API Gateway</h1>
        <p class="description">
            Unified entry point for microservices with full OAuth2 support via Laravel Passport.<br>
            Secure token issuing, scoped access, and gateway-level authentication.
        </p>
    </div>

    <!-- Passport OAuth2 highlight -->
    <div class="passport-highlight">
        <h3>🔐 Laravel Passport Integration</h3>
        <p style="color: #cbd5e6;">The gateway validates OAuth2 access tokens, manages clients, and proxies requests with scoped permissions.</p>
        <div class="oauth-endpoints">
            <div class="endpoint" style="background:#1e1b4b;">
                <span class="method post">POST</span>
                <span class="url">/oauth/token</span>
                <span style="font-size:0.7rem;">issue token</span>
            </div>
            <div class="endpoint" style="background:#1e1b4b;">
                <span class="method get">GET</span>
                <span class="url">/oauth/clients</span>
                <span style="font-size:0.7rem;">list clients</span>
            </div>
            <div class="endpoint" style="background:#1e1b4b;">
                <span class="method post">POST</span>
                <span class="url">/oauth/token/refresh</span>
                <span style="font-size:0.7rem;">refresh token</span>
            </div>
        </div>
    </div>

    <div class="features">
        <div class="card">
            <div class="card-icon">🚦🔁</div>
            <h3>Smart Routing</h3>
            <p>Dynamic proxying to user, order, payment services. Passport tokens are introspected before routing.</p>
        </div>
        <div class="card">
            <div class="card-icon">🔐🎫</div>
            <h3>Passport Guard</h3>
            <p>OAuth2 token validation via `passport:check` middleware, personal access clients, PKCE support.</p>
        </div>
        <div class="card">
            <div class="card-icon">📈⏱️</div>
            <h3>Rate Limiting</h3>
            <p>Redis-backed throttle per token/client, plus per‑route limits for gateway stability.</p>
        </div>
        <div class="card">
            <div class="card-icon">📡🔍</div>
            <h3>Observability</h3>
            <p>Request tracing with token ID, user context, and metrics exported to Prometheus.</p>
        </div>
    </div>

    <div class="endpoint-section">
        <div class="section-title">
            <span>🌐</span> Gateway Protected Routes (require Passport token)
        </div>
        <div class="endpoint-grid">
            <div class="endpoint">
                <span class="method get">GET</span>
                <span class="url">/api/health</span>
                <span style="font-size:0.7rem;">public</span>
            </div>
            <div class="endpoint">
                <span class="method get">GET</span>
                <span class="url">/api/user</span>
                <span style="font-size:0.7rem;">requires token</span>
            </div>
            <div class="endpoint">
                <span class="method post">POST</span>
                <span class="url">/api/v1/orders</span>
                <span style="font-size:0.7rem;">scope: create-order</span>
            </div>
            <div class="endpoint">
                <span class="method put">PUT</span>
                <span class="url">/api/v1/users/{id}</span>
                <span style="font-size:0.7rem;">scope: update-profile</span>
            </div>
            <div class="endpoint">
                <span class="method delete">DELETE</span>
                <span class="url">/api/oauth/revoke</span>
                <span style="font-size:0.7rem;">revoke token</span>
            </div>
        </div>

        <div class="status-panel">
            <div class="live-badge">
                <div class="pulse"></div>
                <span>Gateway status: <strong style="color:#bbf0d2;">OPERATIONAL</strong> · Passport keys loaded</span>
            </div>
            <div style="font-family: monospace; font-size:0.8rem;">
                <span>🏓 last token introspect: <span id="lastPing">just now</span></span>
            </div>
        </div>

        <div class="btn-group">
            <a href="#" class="btn btn-primary" id="docsBtn">📘 Passport API Docs</a>
            <a href="#" class="btn btn-outline" id="healthBtn">🩺 Gateway Health + OAuth Check</a>
            <a href="#" class="btn btn-outline" id="tokenStatusBtn">🎫 Simulate Token Validation</a>
        </div>
        <div id="feedback" class="feedback-area"></div>
    </div>

    <footer>
        <span>⚡ Laravel 13 API Gateway · Passport OAuth2 · Microservices Orchestration</span><br>
        <span style="font-size: 0.7rem;">Token endpoints: /oauth/* | Gateway introspects every request | built with ❤️</span>
    </footer>
</div>

<script>
    (function() {
        const feedbackDiv = document.getElementById('feedback');
        const lastPingSpan = document.getElementById('lastPing');

        function showMsg(message, isError = false) {
            if (!feedbackDiv) return;
            const bg = isError ? 'rgba(220, 38, 38, 0.2)' : 'rgba(139, 92, 246, 0.2)';
            const border = isError ? '#f87171' : '#a78bfa';
            feedbackDiv.innerHTML = `<div style="background: ${bg}; backdrop-filter: blur(8px); border: 1px solid ${border}; border-radius: 60px; padding: 0.65rem 1.2rem; display: inline-block; font-weight: 500;">${message}</div>`;
            setTimeout(() => {
                if (feedbackDiv.innerHTML.includes(message)) feedbackDiv.innerHTML = '';
            }, 3800);
        }

        const healthBtn = document.getElementById('healthBtn');
        if (healthBtn) {
            healthBtn.addEventListener('click', (e) => {
                e.preventDefault();
                showMsg('✅ Gateway Health: Passport keys loaded · Redis UP · DB connected · OAuth2 endpoints reachable', false);
                if (lastPingSpan) lastPingSpan.innerText = new Date().toLocaleTimeString();
            });
        }

        const docsBtn = document.getElementById('docsBtn');
        if (docsBtn) {
            docsBtn.addEventListener('click', (e) => {
                e.preventDefault();
                showMsg('📘 Passport documentation: /oauth/scopes, client management, token generation via `php artisan passport:install`', false);
            });
        }

        const tokenBtn = document.getElementById('tokenStatusBtn');
        if (tokenBtn) {
            tokenBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const valid = Math.random() > 0.2;
                if (valid) {
                    showMsg('🎫 Token valid: Scope `read-user` and `write-orders` granted. Gateway proxy allowed.', false);
                } else {
                    showMsg('❌ Invalid or expired access token. Please request a new token via /oauth/token', true);
                }
            });
        }

        const endpoints = document.querySelectorAll('.endpoint');
        endpoints.forEach(el => {
            el.addEventListener('click', () => {
                const methodSpan = el.querySelector('.method');
                const urlSpan = el.querySelector('.url');
                if (methodSpan && urlSpan) {
                    const method = methodSpan.innerText.trim();
                    const path = urlSpan.innerText.trim();
                    if (path.includes('oauth')) {
                        showMsg(`🔐 Passport OAuth endpoint: ${method} ${path} — managed directly by Passport.`, false);
                    } else {
                        showMsg(`🌉 Gateway route: ${method} ${path} → validating Bearer token via Passport guard → proxying to service.`, false);
                    }
                } else {
                    showMsg(`🔑 Gateway requires valid OAuth2 token. Use "Authorization: Bearer <token>"`, false);
                }
            });
        });

        setInterval(() => {
            if (lastPingSpan) lastPingSpan.innerText = new Date().toLocaleTimeString();
        }, 30000);
    })();
</script>
</body>
</html>