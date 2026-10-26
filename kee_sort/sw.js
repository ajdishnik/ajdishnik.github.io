// sw.js — кэшируем только файлы приложения, никаких пользовательских данных
const CACHE = 'kdbx-editor-v1';

const ASSETS = [
  './',
  './index.html',
  './lib/kdbx_wasm.js',
  './lib/kdbx_wasm_bg.wasm',
  './lib/sort.js',
  './lib/2.jpg'
  // добавьте сюда ВСЕ ассеты приложения:
  // шрифты, иконки, дополнительные .js/.css/.wasm, которые грузятся
];

self.addEventListener('install', (e) => {
  e.waitUntil(
    caches.open(CACHE)
      .then(c => c.addAll(ASSETS))
      .then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (e) => {
  e.waitUntil(
    caches.keys()
      .then(keys => Promise.all(
        keys.filter(k => k !== CACHE).map(k => caches.delete(k))
      ))
      .then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (e) => {
  const req = e.request;
  if (req.method !== 'GET') return;

  // Стратегия: cache-first для своих файлов, network для остальных
  e.respondWith(
    caches.match(req).then(cached => {
      if (cached) return cached;
      return fetch(req).then(resp => {
        // Кэшируем только успешные same-origin GET-ответы
        if (resp.ok && new URL(req.url).origin === location.origin) {
          const copy = resp.clone();
          caches.open(CACHE).then(c => c.put(req, copy));
        }
        return resp;
      }).catch(() => {
        // Если совсем нет сети и файла нет в кэше — отдаём index.html
        if (req.mode === 'navigate') return caches.match('./index.html');
      });
    })
  );
});