// Efeitos visuais do DeployExpress. Tudo desliga se o sistema pedir "reduzir movimento".
(() => {
    if (matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    // ---- fundo: partículas conectadas (rede) ----
    const canvas = document.getElementById('bg');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let w, h, pts = [], mouse = { x: -9999, y: -9999 };
        const N = Math.min(90, Math.floor(innerWidth / 14));
        const resize = () => { w = canvas.width = innerWidth; h = canvas.height = innerHeight; };
        resize();
        addEventListener('resize', resize);
        addEventListener('mousemove', e => { mouse.x = e.clientX; mouse.y = e.clientY; });
        for (let i = 0; i < N; i++) pts.push({ x: Math.random() * w, y: Math.random() * h, vx: (Math.random() - .5) * .3, vy: (Math.random() - .5) * .3, r: 1 + Math.random() * 1.2, c: Math.random() < .12 ? '255,106,0' : '255,255,255' });
        const draw = () => {
            ctx.clearRect(0, 0, w, h);
            for (const p of pts) {
                p.x += p.vx; p.y += p.vy;
                if (p.x < 0 || p.x > w) p.vx *= -1;
                if (p.y < 0 || p.y > h) p.vy *= -1;
                const dm = Math.hypot(p.x - mouse.x, p.y - mouse.y);
                if (dm < 140) { p.x += (p.x - mouse.x) / dm * .6; p.y += (p.y - mouse.y) / dm * .6; }
                ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(${p.c},.45)`; ctx.fill();
            }
            for (let i = 0; i < pts.length; i++) for (let j = i + 1; j < pts.length; j++) {
                const a = pts[i], b = pts[j], d = Math.hypot(a.x - b.x, a.y - b.y);
                if (d < 130) { ctx.strokeStyle = `rgba(255,255,255,${(1 - d / 130) * .12})`; ctx.lineWidth = 1; ctx.beginPath(); ctx.moveTo(a.x, a.y); ctx.lineTo(b.x, b.y); ctx.stroke(); }
            }
            requestAnimationFrame(draw);
        };
        draw();
    }

    // ---- números do painel sobem do zero ----
    document.querySelectorAll('.stat .n').forEach(el => {
        const end = parseInt(el.textContent, 10);
        if (isNaN(end) || end === 0) return;
        const t0 = performance.now(), dur = 1000;
        const tick = now => {
            const p = Math.min(1, (now - t0) / dur);
            el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3)));
            if (p < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
    });

    // ---- cards com inclinação 3D seguindo o mouse ----
    document.querySelectorAll('.stat').forEach(card => {
        card.addEventListener('mousemove', e => {
            const r = card.getBoundingClientRect();
            const x = (e.clientX - r.left) / r.width - .5, y = (e.clientY - r.top) / r.height - .5;
            card.style.setProperty('--mx', `${(x + .5) * 100}%`);
            card.style.setProperty('--my', `${(y + .5) * 100}%`);
            card.style.transform = `rotateY(${x * 10}deg) rotateX(${-y * 10}deg) translateY(-3px)`;
        });
        card.addEventListener('mouseleave', () => { card.style.transform = ''; });
    });

    // ---- ondulação nos botões ----
    document.addEventListener('click', ev => {
        const btn = ev.target.closest('.btn');
        if (!btn) return;
        const r = btn.getBoundingClientRect(), s = Math.max(r.width, r.height);
        const dot = document.createElement('span');
        dot.className = 'ripple';
        dot.style.cssText = `width:${s}px;height:${s}px;left:${ev.clientX - r.left - s / 2}px;top:${ev.clientY - r.top - s / 2}px`;
        btn.appendChild(dot);
        setTimeout(() => dot.remove(), 650);
    });

    // ---- barra de progresso ao navegar ----
    const bar = document.getElementById('nav-progress');
    const start = () => bar && bar.classList.add('on');
    document.addEventListener('click', ev => {
        const a = ev.target.closest('a[href]');
        if (a && a.origin === location.origin && !a.target && !ev.metaKey && !ev.ctrlKey) start();
    });
    document.addEventListener('submit', start);
    addEventListener('pageshow', () => bar && bar.classList.remove('on'));

    // ---- mensagem de sucesso some sozinha ----
    const ok = document.querySelector('.alert.ok');
    if (ok) setTimeout(() => { ok.classList.add('fade'); setTimeout(() => ok.remove(), 600); }, 4500);

    // ---- texto "digitando" no login ----
    const typed = document.querySelector('.typed');
    if (typed) {
        const msgs = ['> conectando fábrica e expedição...', '> monitorando perdas em tempo real', '> rastreando entregas por código', '> sistema operacional ✔'];
        let i = 0, j = 0, del = false;
        const step = () => {
            const m = msgs[i];
            typed.textContent = m.slice(0, j);
            if (!del && j < m.length) { j++; setTimeout(step, 38); }
            else if (!del) { del = true; setTimeout(step, 1600); }
            else if (j > 0) { j--; setTimeout(step, 16); }
            else { del = false; i = (i + 1) % msgs.length; setTimeout(step, 300); }
        };
        step();
    }
})();
