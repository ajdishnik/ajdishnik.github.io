// sort.js — сортировка записей
// Типы: 'az' | 'za' | 'date-asc' | 'date-desc'

export const SORT_TYPES = [
    { value: 'az',        label: 'А → Я' },
    { value: 'za',        label: 'Я → А' },
    { value: 'date-asc',  label: 'Дата ↑' },
    { value: 'date-desc', label: 'Дата ↓' },
];

const STORAGE_KEY = 'kdbx-sort-type';

/**
 * Получить сохранённый тип сортировки (по умолчанию — a-z).
 */
export function getSortType() {
    try {
        const v = localStorage.getItem(STORAGE_KEY);
        if (v && SORT_TYPES.some(t => t.value === v)) return v;
    } catch (_) {}
    return 'az';
}

/**
 * Сохранить тип сортировки.
 */
export function setSortType(type) {
    try { localStorage.setItem(STORAGE_KEY, type); } catch (_) {}
}

/**
 * Извлечь дату создания записи из метаданных KDBX.
 * Поддерживаются разные варианты полей, которые может вернуть wasm-обёртка:
 *  - entry.creationTime (строка ISO / число ms / объект Date)
 *  - entry.creation_time
 *  - entry.times?.creationTime
 *  - entry.times?.creation_time
 *  - entry.meta?.creationTime
 *  - entry.customFields / entry.strings['CreationTime']
 * Возвращает timestamp в ms, либо 0, если дата не найдена.
 */
export function getEntryCreatedTime(entry) {
    if (!entry) return 0;

    const candidates = [
        entry.creationTime,
        entry.creation_time,
        entry.createdAt,
        entry.created_at,
        entry.times?.creationTime,
        entry.times?.creation_time,
        entry.times?.created,
        entry.meta?.creationTime,
        entry.meta?.creation_time,
        entry.strings?.CreationTime?.value,
        entry.strings?.CreationTime,
        entry.customFields?.CreationTime,
        entry.fields?.CreationTime,
    ];

    for (const c of candidates) {
        const t = toTimestamp(c);
        if (t > 0) return t;
    }
    return 0;
}

function toTimestamp(value) {
    if (value == null) return 0;
    if (typeof value === 'number') {
        // Может быть в секундах или в миллисекундах
        return value < 1e12 ? value * 1000 : value;
    }
    if (value instanceof Date) {
        const t = value.getTime();
        return isNaN(t) ? 0 : t;
    }
    if (typeof value === 'string') {
        // Некоторые wasm-обёртки возвращают base64 или ISO строку.
        const s = value.trim();
        if (!s) return 0;
        // Пробуем как ISO / RFC
        const t = Date.parse(s);
        if (!isNaN(t)) return t;
        // Пробуем как число в строке
        if (/^\d+$/.test(s)) return toTimestamp(Number(s));
    }
    if (typeof value === 'object' && typeof value.value === 'string') {
        return toTimestamp(value.value);
    }
    return 0;
}

/**
 * Отсортировать массив записей по выбранному типу.
 * Возвращает НОВЫЙ массив (не мутирует исходный).
 */
export function sortEntries(entries, type = 'az') {
    if (!Array.isArray(entries) || entries.length < 2) return entries.slice();

    const collator = new Intl.Collator('ru', { sensitivity: 'base', numeric: true });

    const cmpTitle = (a, b) => {
        const ta = (a.title || '').trim();
        const tb = (b.title || '').trim();
        return collator.compare(ta, tb);
    };

    const cmpDate = (a, b) => {
        const ta = getEntryCreatedTime(a);
        const tb = getEntryCreatedTime(b);
        return ta - tb;
    };

    const sorted = entries.slice();

    switch (type) {
        case 'za':
            sorted.sort((a, b) => cmpTitle(b, a));
            break;
        case 'date-asc':
            sorted.sort((a, b) => cmpDate(a, b) || cmpTitle(a, b));
            break;
        case 'date-desc':
            sorted.sort((a, b) => cmpDate(b, a) || cmpTitle(b, a));
            break;
        case 'az':
        default:
            sorted.sort(cmpTitle);
            break;
    }

    return sorted;
}

/**
 * Создать/обновить <select> сортировки.
 * @param {HTMLSelectElement} selectEl
 * @param {string} currentType
 * @param {(type:string)=>void} onChange
 */
export function mountSortSelect(selectEl, currentType, onChange) {
    if (!selectEl) return;
    selectEl.innerHTML = '';
    for (const opt of SORT_TYPES) {
        const o = document.createElement('option');
        o.value = opt.value;
        o.textContent = opt.label;
        if (opt.value === currentType) o.selected = true;
        selectEl.appendChild(o);
    }
    selectEl.addEventListener('change', () => {
        const t = selectEl.value;
        setSortType(t);
        onChange(t);
    });
}
