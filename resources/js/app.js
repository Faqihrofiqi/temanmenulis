import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

const THEME_STORAGE_KEY = 'deadlineku-theme';

const getPreferredTheme = () => {
	const stored = window.localStorage.getItem(THEME_STORAGE_KEY);
	if (stored === 'light' || stored === 'dark') {
		return stored;
	}

	return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
};

const applyTheme = (theme) => {
	const root = document.documentElement;
	const isDark = theme === 'dark';
	root.classList.toggle('dark', isDark);
	root.dataset.theme = theme;

	document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
		button.setAttribute('aria-pressed', String(isDark));
		const label = button.querySelector('[data-theme-toggle-label]');
		if (label) {
			label.textContent = isDark ? 'Mode Gelap' : 'Mode Terang';
		}
	});
};

const setTheme = (theme) => {
	window.localStorage.setItem(THEME_STORAGE_KEY, theme);
	applyTheme(theme);
};

document.addEventListener('DOMContentLoaded', () => {
	applyTheme(getPreferredTheme());

	document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
		button.addEventListener('click', () => {
			const root = document.documentElement;
			const nextTheme = root.classList.contains('dark') ? 'light' : 'dark';
			setTheme(nextTheme);
		});
	});
});

Alpine.data('serviceExplorer', () => ({
	loading: true,
	error: null,
	services: [],
	meta: {},
	perPage: 6,
	featuredOnly: true,
	selectedCategory: 'all',
	categories: [
		{ label: 'Semua Paket', value: 'all' },
		{ label: 'Skripsi', value: 'skripsi' },
		{ label: 'Thesis', value: 'thesis' },
		{ label: 'Disertasi', value: 'disertasi' },
	],
	init() {
		this.fetchServices();
	},
	async fetchServices(targetUrl = null) {
		this.loading = true;
		this.error = null;

		try {
			const endpoint = new URL(targetUrl ?? '/api/services', window.location.origin);
			endpoint.searchParams.set('per_page', this.perPage.toString());

			if (this.selectedCategory !== 'all') {
				endpoint.searchParams.set('category', this.selectedCategory);
			} else {
				endpoint.searchParams.delete('category');
			}

			if (this.featuredOnly) {
				endpoint.searchParams.set('featured', '1');
			} else {
				endpoint.searchParams.delete('featured');
			}

			const { data } = await window.axios.get(endpoint.pathname + endpoint.search);

			this.services = data.data ?? [];
			this.meta = data;
		} catch (error) {
			console.error('Failed to fetch services', error);
			this.error = 'Gagal memuat daftar layanan. Coba ulang beberapa saat lagi.';
			this.services = [];
		} finally {
			this.loading = false;
		}
	},
	setCategory(value) {
		if (this.selectedCategory === value) {
			return;
		}

		this.selectedCategory = value;
		this.fetchServices();
	},
	toggleFeatured() {
		this.featuredOnly = !this.featuredOnly;
		this.fetchServices();
	},
	goTo(url) {
		if (!url) {
			return;
		}

		this.fetchServices(url);
	},
	paginationLabel(link) {
		if (typeof link.label === 'string') {
			return link.label.replace('&raquo;', '›').replace('&laquo;', '‹');
		}

		return link.label;
	},
	pageSummary() {
		return {
			from: this.meta.from ?? 0,
			to: this.meta.to ?? 0,
			total: this.meta.total ?? 0,
		};
	},
	formatCurrency(value) {
		return new Intl.NumberFormat('id-ID', {
			style: 'currency',
			currency: 'IDR',
			maximumFractionDigits: 0,
		}).format(Number(value ?? 0));
	},
}));

Alpine.start();
