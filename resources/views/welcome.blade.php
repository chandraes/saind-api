<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>API Core Service</title>
    <style>
        :root {
            --bg-color: #000000;
            --card-bg: rgba(28, 28, 30, 0.5);
            --border-color: rgba(255, 255, 255, 0.08);
            --text-main: #f5f5f7;
            --text-muted: #86868b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            z-index: 0;
        }

        .orb-1 {
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.03);
            top: -150px;
            left: -150px;
        }

        .orb-2 {
            width: 350px;
            height: 350px;
            background: rgba(10, 132, 255, 0.06);
            bottom: -100px;
            right: -100px;
        }

        .glass-panel {
            position: relative;
            z-index: 1;
            background: var(--card-bg);
            backdrop-filter: saturate(180%) blur(24px);
            -webkit-backdrop-filter: saturate(180%) blur(24px);
            border: 1px solid var(--border-color);
            border-radius: 28px;
            padding: 48px 40px;
            width: 90%;
            max-width: 420px;
            text-align: center;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5),
                        inset 0 1px 0 rgba(255, 255, 255, 0.1);
            animation: fade-in 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        @keyframes fade-in {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        h1 {
            font-size: 22px;
            font-weight: 600;
            letter-spacing: -0.5px;
            margin-bottom: 10px;
        }

        p {
            font-size: 15px;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 32px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border-color);
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-main);
            letter-spacing: 0.2px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background-color: #32d74b;
            border-radius: 50%;
            margin-right: 10px;
            box-shadow: 0 0 12px rgba(50, 215, 75, 0.8);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(50, 215, 75, 0.4); }
            70% { box-shadow: 0 0 0 6px rgba(50, 215, 75, 0); }
            100% { box-shadow: 0 0 0 0 rgba(50, 215, 75, 0); }
        }

        .code-block {
            margin-top: 32px;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 14px;
            padding: 16px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 13px;
            color: #32d74b;
            text-align: left;
            border: 1px solid var(--border-color);
            position: relative;
        }

        .code-block::before {
            content: '';
            position: absolute;
            top: -1px; left: 20px;
            width: 40px; height: 1px;
            background: linear-gradient(90deg, transparent, #32d74b, transparent);
        }

        /* --- Elemen Interaktif Baru --- */

        .icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 24px;
            background: radial-gradient(circle at center, rgba(10, 132, 255, 0.15) 0%, rgba(28, 28, 30, 0.8) 70%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4),
                        inset 0 1px 1px rgba(255, 255, 255, 0.1);
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }

        .system-core-pulsar {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Cincin-cincin pulsar */
        .pulsar-ring {
            position: absolute;
            border-radius: 50%;
            opacity: 0;
            background: radial-gradient(circle at center, transparent 30%, rgba(10, 132, 255, 0.3) 100%);
            border: 1px solid rgba(10, 132, 255, 0.1);
            box-shadow: 0 0 15px rgba(10, 132, 255, 0.2);
            backdrop-filter: blur(2px);
            transition: all 0.5s ease-out;
            transform-origin: center;
        }

        /* Konfigurasi cincin */
        .pulsar-ring:nth-child(1) { width: 30px; height: 30px; animation: ring-pulse 4s infinite linear; animation-delay: 0s; }
        .pulsar-ring:nth-child(2) { width: 50px; height: 50px; animation: ring-pulse 4s infinite linear; animation-delay: 1s; }
        .pulsar-ring:nth-child(3) { width: 70px; height: 70px; animation: ring-pulse 4s infinite linear; animation-delay: 2s; }
        .pulsar-ring:nth-child(4) { width: 90px; height: 90px; animation: ring-pulse 4s infinite linear; animation-delay: 3s; }

        @keyframes ring-pulse {
            0% { transform: scale(0.6); opacity: 0; }
            30% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.4); opacity: 0; }
        }

        /* Interaksi Inti: Saat pointer diarahkan (hover) */
        .icon-wrapper:hover .pulsar-ring {
            border-color: rgba(50, 215, 75, 0.3); /* Hijau */
            box-shadow: 0 0 25px rgba(50, 215, 75, 0.4);
            animation-duration: 1.5s; /* Denyut lebih cepat */
        }

        .icon-wrapper:hover {
            background: radial-gradient(circle at center, rgba(50, 215, 75, 0.15) 0%, rgba(28, 28, 30, 0.9) 70%);
        }

        /* --- Akhir Elemen Interaktif --- */
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="glass-panel">
        <!-- Interaksi Visual: System Core Pulsar -->
        <div class="icon-wrapper" id="core-pulsar">
            <div class="system-core-pulsar">
                <div class="pulsar-ring"></div>
                <div class="pulsar-ring"></div>
                <div class="pulsar-ring"></div>
                <div class="pulsar-ring"></div>
            </div>
        </div>

        <h1>API Core System</h1>
        <p>Akses langsung via peramban web dibatasi. Harap gunakan koneksi *client* dan *endpoint* API yang telah dikonfigurasi.</p>

        <div class="status-badge">
            <span class="status-dot"></span>
            Layanan Optimal
        </div>

        <div class="code-block">
            {<br>
            &nbsp;&nbsp;"status": <span style="color: #f5f5f7">200</span>,<br>
            &nbsp;&nbsp;"service": <span style="color: #f5f5f7">"API Core Service"</span>,<br>
            &nbsp;&nbsp;"response": <span style="color: #f5f5f7">"Ready to handle requests"</span><br>
            }
        </div>
    </div>

    <!-- Menambahkan JavaScript untuk responsivitas interaksi -->
    <script>
        const pulsar = document.getElementById('core-pulsar');

        pulsar.addEventListener('mousemove', (e) => {
            const rect = pulsar.getBoundingClientRect();
            const x = (e.clientX - rect.left - rect.width / 2) / 10;
            const y = (e.clientY - rect.top - rect.height / 2) / 10;
            pulsar.style.transform = `perspective(200px) rotateX(${-y}deg) rotateY(${x}deg)`;
        });

        pulsar.addEventListener('mouseleave', () => {
            pulsar.style.transform = `perspective(200px) rotateX(0deg) rotateY(0deg)`;
        });
    </script>
</body>
</html>
