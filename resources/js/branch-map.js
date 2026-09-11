import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const section = document.querySelector('[data-branch-map]');
const container = document.getElementById('branch-map');

if (section && container) {
    const cards = [...section.querySelectorAll('[data-branch]')];
    const branches = cards.map((c) => ({
        slug: c.dataset.branch,
        name: c.querySelector('h3').textContent.trim(),
        town: c.dataset.town,
        lat: parseFloat(c.dataset.lat),
        lon: parseFloat(c.dataset.lon),
        card: c,
    }));

    // The static image is the no-JS fallback; Leaflet takes over from here.
    container.querySelector('[data-map-fallback]')?.remove();

    const map = L.map(container, {
        scrollWheelZoom: false, // enabled on click, so the page still scrolls over the map
        minZoom: 8,
        maxZoom: 18,
        zoomControl: true,
        attributionControl: true,
    });

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    const markers = new Map();

    branches.forEach((b) => {
        const marker = L.marker([b.lat, b.lon], {
            icon: L.divIcon({
                className: 'branch-pin',
                html: '<span class="branch-pin__dot"></span>',
                iconSize: [18, 18],
                iconAnchor: [9, 9],
            }),
            keyboard: false,
            title: b.name,
        }).addTo(map);

        marker.bindTooltip(`<b>${b.name}</b><br>${b.town}`, { direction: 'top', offset: [0, -10] });
        marker.on('mouseover', () => highlight(b.slug));
        marker.on('mouseout', clear);
        marker.on('click', () => { window.location.href = b.card.href; });
        markers.set(b.slug, marker);
    });

    // Default view: zoomed out far enough to show all six branches at once.
    const bounds = L.latLngBounds(branches.map((b) => [b.lat, b.lon]));
    map.fitBounds(bounds, { padding: [42, 42], maxZoom: 13 });
    const defaultZoom = map.getZoom();
    const defaultCentre = map.getCenter();

    function highlight(slug) {
        branches.forEach((b) => {
            const on = b.slug === slug;
            b.card.classList.toggle('is-active', on);
            markers.get(b.slug).getElement()?.classList.toggle('is-active', on);
        });
        markers.get(slug)?.openTooltip();
    }

    function clear() {
        branches.forEach((b) => {
            b.card.classList.remove('is-active');
            markers.get(b.slug).getElement()?.classList.remove('is-active');
            markers.get(b.slug).closeTooltip();
        });
    }

    cards.forEach((card) => {
        ['pointerenter', 'focus'].forEach((e) => card.addEventListener(e, () => highlight(card.dataset.branch)));
        ['pointerleave', 'blur'].forEach((e) => card.addEventListener(e, clear));
    });

    // Wheel zoom only once the map has been clicked, so it never hijacks page scroll.
    map.on('click', () => map.scrollWheelZoom.enable());
    map.on('mouseout', () => map.scrollWheelZoom.disable());

    section.querySelector('[data-map-reset]')?.addEventListener('click', () => {
        map.setView(defaultCentre, defaultZoom, { animate: true });
    });
}
