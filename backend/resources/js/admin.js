import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    const baseOptions = { responsive: true, maintainAspectRatio: false };

    const readData = (el) => ({
        labels: JSON.parse(el.dataset.labels || '[]'),
        values: JSON.parse(el.dataset.values || '[]'),
    });

    const lineEl = document.getElementById('registrationsChart');
    if (lineEl) {
        const { labels, values } = readData(lineEl);
        new Chart(lineEl, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Регистрации',
                    data: values,
                    borderColor: '#000000',
                    backgroundColor: 'rgba(0,0,0,0.05)',
                    tension: 0.3,
                    fill: true,
                }],
            },
            options: { ...baseOptions, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } },
        });
    }

    const postsEl = document.getElementById('postsChart');
    if (postsEl) {
        const { labels, values } = readData(postsEl);
        new Chart(postsEl, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Сообщения',
                    data: values,
                    borderColor: '#000000',
                    backgroundColor: 'rgba(0,0,0,0.05)',
                    tension: 0.3,
                    fill: true,
                }],
            },
            options: { ...baseOptions, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } },
        });
    }

    const categoriesEl = document.getElementById('categoriesChart');
    if (categoriesEl) {
        const { labels, values } = readData(categoriesEl);
        new Chart(categoriesEl, {
            type: 'bar',
            data: { labels, datasets: [{ label: 'Тем', data: values, backgroundColor: '#000000' }] },
            options: { ...baseOptions, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } },
        });
    }

    const rolesEl = document.getElementById('rolesChart');
    if (rolesEl) {
        const { labels, values } = readData(rolesEl);
        new Chart(rolesEl, {
            type: 'doughnut',
            data: { labels, datasets: [{ data: values, backgroundColor: ['#000000', '#737373', '#a3a3a3', '#d4d4d4'] }] },
            options: baseOptions,
        });
    }

    const rejectedEl = document.getElementById('rejectedChart');
    if (rejectedEl) {
        const { labels, values } = readData(rejectedEl);
        new Chart(rejectedEl, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Заблокировано',
                    data: values,
                    backgroundColor: '#dc2626',
                }],
            },
            options: { ...baseOptions, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } },
        });
    }
});