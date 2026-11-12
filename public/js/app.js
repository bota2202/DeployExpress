// Efeitos visuais do DeployExpress. Tudo desliga se o sistema pedir "reduzir movimento".
(() => {
    // ---- abertura (só na primeira visita da sessão) ----
    const intro = document.querySelector('.intro');
    try {
        if (intro && sessionStorage.getItem('dx-intro')) intro.remove();
        sessionStorage.setItem('dx-intro', '1');
    } catch (e) { /* storage bloqueado: mostra a abertura e segue */ }

    if (matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    const root = document.documentElement;

    // ---- holofote + grade seguindo o mouse ----
    let raf = 0, mx = 0, my = 0;
    addEventListener('pointermove', e => {
        mx = e.clientX; my = e.clientY;
        if (raf) return;
        raf = requestAnimationFrame(() => {
            raf = 0;
            root.style.setProperty('--sx', mx + 'px');
            root.style.setProperty('--sy', my + 'px');
        });
    });

    // ---- etiquetas do login com paralaxe ----
    const side = document.querySelector('.login-side');
    if (side) side.addEventListener('pointermove', e => {
        side.style.setProperty('--px', (e.clientX / innerWidth - .5) * 2);
        side.style.setProperty('--py', (e.clientY / innerHeight - .5) * 2);
    });

    // ---- títulos entram palavra por palavra ----
    document.querySelectorAll('h1:not(.login h1), .mega').forEach(h => {
        let i = 0;
        [...h.childNodes].forEach(node => {
            const wrap = el => { const s = document.createElement('span'); s.className = 'w'; s.style.setProperty('--i', i++); s.append(el); return s; };
            if (node.nodeType === 3) {
                const parts = node.textContent.split(/(\s+)/).filter(Boolean);
                node.replaceWith(...parts.map(p => /^\s+$/.test(p) ? document.createTextNode(p) : wrap(p)));
            } else if (node.nodeName !== 'BR') {
                node.replaceWith(wrap(node.cloneNode(true)));
            }
        });
    });

    // ---- cascata: índice de cada item para o atraso da animação ----
    document.querySelectorAll('.bento, .products, .pipe, .timeline, .feats').forEach(g => [...g.children].forEach((c, i) => c.style.setProperty('--i', i)));
    document.querySelectorAll('table').forEach(t => [...t.querySelectorAll('tr:has(td)')].forEach((r, i) => r.style.setProperty('--i', Math.min(i, 8))));

    // ---- números sobem do zero ----
    document.querySelectorAll('.stat .n, .seg .n, .product .n').forEach(el => {
        const end = parseInt(el.textContent, 10);
        if (isNaN(end) || end === 0) return;
        const t0 = performance.now() + 250, dur = 1300;
        const tick = now => {
            const p = Math.max(0, Math.min(1, (now - t0) / dur));
            el.textContent = Math.round(end * (1 - Math.pow(1 - p, 4)));
            if (p < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
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
