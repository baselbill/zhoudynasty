/* Zhou Dynasty — shared image lightbox.
   Self-contained: injects its own CSS + DOM, no external deps.
   Works on v1 and v2 pages, in post bodies, post rows, and photo albums. */
(function () {
  'use strict';
  if (window.__zdLightbox) return;
  window.__zdLightbox = true;

  var CSS = ''
    + '.zd-lb { position: fixed; inset: 0; background: rgba(0,0,0,0.92); z-index: 10000;'
    +         ' display: none; align-items: center; justify-content: center;'
    +         ' user-select: none; -webkit-user-select: none; touch-action: pan-y; }'
    + '.zd-lb.zd-open { display: flex; }'
    + '.zd-lb-figure { position: relative; max-width: 92vw; max-height: 92vh;'
    +         ' display: flex; flex-direction: column; align-items: center; justify-content: center;'
    +         ' margin: 0; }'
    + '.zd-lb-img { max-width: 92vw; max-height: 84vh; display: block;'
    +         ' border: 3px solid #fff; box-shadow: 0 4px 40px rgba(0,0,0,0.55);'
    +         ' background: #111; cursor: pointer; transition: opacity 100ms; }'
    + '.zd-lb-loading .zd-lb-img { opacity: 0.45; }'
    + '.zd-lb-cap { color: rgba(255,255,255,0.85); font: 12px/1.5 ui-monospace, Menlo, Consolas, monospace;'
    +         ' margin-top: 12px; letter-spacing: 0.3px; text-align: center; max-width: 80vw; }'
    + '.zd-lb-cap b { color: #ffd23a; font-weight: 600; }'
    + '.zd-lb-btn { position: fixed; background: rgba(0,0,0,0.55); border: 2px solid rgba(255,255,255,0.45);'
    +         ' color: #fff; cursor: pointer; padding: 4px 14px; line-height: 1;'
    +         ' font: 28px/1 -apple-system, BlinkMacSystemFont, system-ui, sans-serif;'
    +         ' transition: background 100ms, border-color 100ms, transform 100ms; z-index: 10001; }'
    + '.zd-lb-btn:hover { background: rgba(255,255,255,0.18); border-color: #fff; }'
    + '.zd-lb-btn:active { transform: scale(0.95); }'
    + '.zd-lb-btn:disabled { opacity: 0.25; cursor: default; }'
    + '.zd-lb-btn:focus-visible { outline: 2px solid #ffd23a; outline-offset: 2px; }'
    + '.zd-lb-prev { left: 20px; top: 50%; transform: translateY(-50%); padding: 6px 16px 10px; font-size: 32px; }'
    + '.zd-lb-next { right: 20px; top: 50%; transform: translateY(-50%); padding: 6px 16px 10px; font-size: 32px; }'
    + '.zd-lb-close { right: 20px; top: 20px; padding: 0 12px 4px; font-size: 26px; }'
    + '.zd-lb-prev:active { transform: translateY(-50%) scale(0.95); }'
    + '.zd-lb-next:active { transform: translateY(-50%) scale(0.95); }'
    + '.zd-lb-hint { position: fixed; bottom: 18px; left: 50%; transform: translateX(-50%);'
    +         ' color: rgba(255,255,255,0.55); font: 11px/1.5 ui-monospace, Menlo, Consolas, monospace;'
    +         ' pointer-events: none; letter-spacing: 0.3px; text-align: center; }'
    + '.zd-lb-hint kbd { display: inline-block; padding: 1px 5px; border: 1px solid rgba(255,255,255,0.3);'
    +         ' border-radius: 3px; margin: 0 2px; background: rgba(255,255,255,0.05); }'
    + '@media (max-width: 700px) {'
    +   ' .zd-lb-prev { left: 8px; padding: 4px 10px 8px; font-size: 26px; }'
    +   ' .zd-lb-next { right: 8px; padding: 4px 10px 8px; font-size: 26px; }'
    +   ' .zd-lb-close { right: 10px; top: 10px; }'
    +   ' .zd-lb-hint { display: none; }'
    +   ' .zd-lb-img { border-width: 2px; }'
    + '}';

  function injectStyle() {
    var s = document.createElement('style');
    s.setAttribute('data-zd-lightbox', '');
    s.textContent = CSS;
    document.head.appendChild(s);
  }

  function buildOverlay() {
    var o = document.createElement('div');
    o.className = 'zd-lb';
    o.setAttribute('role', 'dialog');
    o.setAttribute('aria-modal', 'true');
    o.setAttribute('aria-label', 'Image viewer');
    o.innerHTML =
      '<button class="zd-lb-btn zd-lb-close" type="button" aria-label="Close (Esc)">×</button>' +
      '<button class="zd-lb-btn zd-lb-prev" type="button" aria-label="Previous (Left arrow)">‹</button>' +
      '<button class="zd-lb-btn zd-lb-next" type="button" aria-label="Next (Right arrow)">›</button>' +
      '<figure class="zd-lb-figure">' +
        '<img class="zd-lb-img" alt="">' +
        '<figcaption class="zd-lb-cap"></figcaption>' +
      '</figure>' +
      '<div class="zd-lb-hint">' +
        '<kbd>←</kbd><kbd>→</kbd> navigate · <kbd>esc</kbd> close · click image for next' +
      '</div>';
    return o;
  }

  // selectors for "lightbox-able" images on either v1 or v2 pages
  var SELECTORS = [
    '.post-body img',         // v1 in-post images
    '.v2-featured-body img',  // v2 featured/post-detail images
    '.v2-row img',            // v2 listing rows (rare; excerpts mostly text)
    '.album-grid img',        // v1 album grids
    '.v2-photo-grid img',     // v2 album photo grids
    '.albums-index img'       // top-level albums-index thumbnails
  ];

  function getImgList() {
    var seen = Object.create(null);
    var list = [];
    var nodes = document.querySelectorAll(SELECTORS.join(', '));
    for (var i = 0; i < nodes.length; i++) {
      var img = nodes[i];
      // skip absent or 1x1 spacers
      if (!img.src) continue;
      if (img.naturalWidth && img.naturalWidth < 24 && img.naturalHeight < 24) continue;
      // prefer a wrapping <a href="..."> if it points to an image (full-resolution variant)
      var src = img.src;
      var a = img.closest && img.closest('a');
      if (a) {
        var href = a.getAttribute('href') || '';
        var clean = href.split('?')[0].split('#')[0];
        if (/\.(jpe?g|png|gif|bmp|webp)$/i.test(clean)) {
          src = a.href; // resolved absolute URL
        }
      }
      if (seen[src]) continue;
      seen[src] = true;
      list.push({ src: src, alt: img.getAttribute('alt') || '', el: img });
    }
    return list;
  }

  function init() {
    if (!document.body) return;
    injectStyle();
    // Drop legacy inline lightbox elements that came with the old pages
    var legacy = document.querySelectorAll('#lightbox, .v2-lightbox');
    for (var i = 0; i < legacy.length; i++) legacy[i].parentNode.removeChild(legacy[i]);

    var overlay = buildOverlay();
    document.body.appendChild(overlay);

    var imgEl    = overlay.querySelector('.zd-lb-img');
    var capEl    = overlay.querySelector('.zd-lb-cap');
    var prevBtn  = overlay.querySelector('.zd-lb-prev');
    var nextBtn  = overlay.querySelector('.zd-lb-next');
    var closeBtn = overlay.querySelector('.zd-lb-close');

    var images = [];
    var index  = 0;
    var lastFocus = null;

    function rebuild() { images = getImgList(); }

    function open(i) {
      rebuild();
      if (!images.length) return;
      lastFocus = document.activeElement;
      index = ((i % images.length) + images.length) % images.length;
      render();
      overlay.classList.add('zd-open');
      document.documentElement.style.overflow = 'hidden';
      // move focus into the dialog for keyboard a11y
      try { closeBtn.focus({ preventScroll: true }); } catch (_) { closeBtn.focus(); }
    }

    function close() {
      overlay.classList.remove('zd-open');
      document.documentElement.style.overflow = '';
      if (lastFocus && lastFocus.focus) { try { lastFocus.focus({ preventScroll: true }); } catch (_) { lastFocus.focus(); } }
    }

    function nav(delta) {
      if (!images.length) return;
      index = ((index + delta) % images.length + images.length) % images.length;
      render();
    }

    function render() {
      var cur = images[index];
      overlay.classList.add('zd-lb-loading');
      var probe = new Image();
      var done = function () {
        imgEl.src = cur.src;
        imgEl.alt = cur.alt;
        overlay.classList.remove('zd-lb-loading');
      };
      probe.onload = done;
      probe.onerror = done;
      probe.src = cur.src;

      var counter = '<b>' + (index + 1) + '</b> / ' + images.length;
      capEl.innerHTML = counter + (cur.alt ? ' · ' + escapeHtml(cur.alt) : '');

      // Preload neighbors for snappier nav
      if (images.length > 1) {
        new Image().src = images[(index + 1) % images.length].src;
        new Image().src = images[(index - 1 + images.length) % images.length].src;
      }

      var single = images.length < 2;
      prevBtn.disabled = single;
      nextBtn.disabled = single;
      prevBtn.style.display = single ? 'none' : '';
      nextBtn.style.display = single ? 'none' : '';
    }

    function escapeHtml(s) {
      return String(s).replace(/[&<>"']/g, function (ch) {
        return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[ch];
      });
    }

    // Bind clicks on every candidate image (or its wrapping anchor).
    function bindAll() {
      var nodes = document.querySelectorAll(SELECTORS.join(', '));
      for (var i = 0; i < nodes.length; i++) {
        var img = nodes[i];
        if (img.dataset && img.dataset.zdLb) continue;
        if (img.dataset) img.dataset.zdLb = '1';
        img.style.cursor = 'zoom-in';
        var target = (img.closest && img.closest('a')) || img;
        // capture-phase click handler so we run before any page-inline handlers
        target.addEventListener('click', clickHandler, true);
      }
    }

    function clickHandler(e) {
      var img = e.currentTarget.tagName === 'A'
        ? e.currentTarget.querySelector('img')
        : e.currentTarget;
      if (!img) return;
      e.preventDefault();
      e.stopPropagation();
      var fresh = getImgList();
      var idx = -1;
      for (var i = 0; i < fresh.length; i++) if (fresh[i].el === img) { idx = i; break; }
      open(idx >= 0 ? idx : 0);
    }

    // Overlay clicks: clicking outside the figure closes; clicking the image goes to next
    overlay.addEventListener('click', function (e) {
      if (e.target === overlay || e.target.classList.contains('zd-lb-figure')) close();
    });
    imgEl.addEventListener('click', function (e) { e.stopPropagation(); nav(1); });
    closeBtn.addEventListener('click', close);
    prevBtn.addEventListener('click', function () { nav(-1); });
    nextBtn.addEventListener('click', function () { nav(1); });

    // Keyboard
    document.addEventListener('keydown', function (e) {
      if (!overlay.classList.contains('zd-open')) return;
      switch (e.key) {
        case 'Escape':                          e.preventDefault(); close(); break;
        case 'ArrowLeft': case 'a': case 'A':   e.preventDefault(); nav(-1); break;
        case 'ArrowRight': case 'd': case 'D':
        case ' ':                               e.preventDefault(); nav(1); break;
        case 'Home':                            e.preventDefault(); index = 0; render(); break;
        case 'End':                             e.preventDefault(); index = images.length - 1; render(); break;
      }
    });

    // Touch swipe
    var tStart = null;
    overlay.addEventListener('touchstart', function (e) {
      if (e.touches.length === 1) tStart = { x: e.touches[0].clientX, y: e.touches[0].clientY };
    }, { passive: true });
    overlay.addEventListener('touchend', function (e) {
      if (!tStart) return;
      var t = e.changedTouches[0];
      var dx = t.clientX - tStart.x;
      var dy = t.clientY - tStart.y;
      if (Math.abs(dx) > 50 && Math.abs(dx) > Math.abs(dy)) nav(dx > 0 ? -1 : 1);
      tStart = null;
    }, { passive: true });

    // wire up images on first load + after any DOM updates
    bindAll();
    // Also rebind if images load lazily later
    if ('MutationObserver' in window) {
      var mo = new MutationObserver(function () { bindAll(); });
      mo.observe(document.body, { childList: true, subtree: true });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
