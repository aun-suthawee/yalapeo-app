/**
 * Academic Results Dashboard Charts
 * Uses Chart.js for visualizing academic achievement data
 */

(function() {
    'use strict';

    // Chart instances
    let ntAreaChart = null;
    let ntDeptChart = null;
    let rtAreaChart = null;
    let rtDeptChart = null;
    let onetAreaChart = null;

    // Current filters
    let currentFilters = {
        year: 2025,
        department: 'all'
    };

    /**
     * Initialize all charts
     */
    function initCharts() {
        loadChartData();
        setupFilterHandlers();
    }

    /**
     * Load chart data from API
     */
    function loadChartData() {
        const params = new URLSearchParams(currentFilters);
        
        console.log('Loading chart data with filters:', currentFilters);
        
        fetch(`/sandbox/academic-results/chart-data?${params}`)
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Chart data received:', data);
                if (data.success) {
                    updateAllCharts(data.data);
                    updateStatistics(data.data.statistics);
                } else {
                    console.error('Failed to load chart data:', data);
                }
            })
            .catch(error => {
                console.error('Error loading chart data:', error);
            });
    }

    /**
     * Update all charts with new data
     */
    function updateAllCharts(data) {
        // Update NT stat cards and charts
        updateNTStatCards(data.subjects['NT']);
        updateNTAreaChart(data.subjects['NT'], data.comparisons.areas);
        updateNTDeptChart(data.subjects['NT'], data.comparisons.departments);
        
        // Update RT stat cards and charts
        updateRTStatCards(data.subjects['RT']);
        updateRTAreaChart(data.subjects['RT'], data.comparisons.areas);
        updateRTDeptChart(data.subjects['RT'], data.comparisons.departments);
        
        // Update O-NET chart and tables
        updateONETAreaChart(data.subjects['O-NET'], data.comparisons.areas);
        updateONETAreaTable(data.subjects['O-NET'], data.comparisons.areas);
        updateONETDeptTable(data.subjects['O-NET'], data.comparisons.departments);
    }

    /**
     * Update NT stat cards
     */
    function updateNTStatCards(ntData) {
        const mathAvg = document.getElementById('ntMathAvg');
        const thaiAvg = document.getElementById('ntThaiAvg');
        
        if (mathAvg) mathAvg.textContent = ntData.math ? ntData.math.toFixed(2) : '0';
        if (thaiAvg) thaiAvg.textContent = ntData.thai ? ntData.thai.toFixed(2) : '0';
    }

    /**
     * Update NT area comparison chart
     */
    function updateNTAreaChart(ntData, areaComparisons) {
        const ctx = document.getElementById('ntAreaChart');
        if (!ctx) return;

        const labels = [];
        const mathData = [];
        const thaiData = [];

        Object.keys(areaComparisons).forEach(area => {
            const areaData = areaComparisons[area];
            if (areaData.nt_count > 0) {
                labels.push(area);
                mathData.push(areaData.nt_math / areaData.nt_count);
                thaiData.push(areaData.nt_thai / areaData.nt_count);
            }
        });

        const chartData = {
            labels: labels,
            datasets: [
                {
                    label: 'คณิตศาสตร์',
                    data: mathData,
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    borderRadius: 6
                },
                {
                    label: 'ภาษาไทย',
                    data: thaiData,
                    backgroundColor: 'rgba(99, 179, 237, 0.8)',
                    borderColor: 'rgba(99, 179, 237, 1)',
                    borderWidth: 2,
                    borderRadius: 6
                }
            ]
        };

        const options = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: { size: 12, family: "'Sarabun', sans-serif" },
                        padding: 10
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 13, family: "'Sarabun', sans-serif" },
                    bodyFont: { size: 12, family: "'Sarabun', sans-serif" },
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + ' คะแนน';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        font: { size: 11, family: "'Sarabun', sans-serif" }
                    },
                    grid: { color: 'rgba(0, 0, 0, 0.05)' }
                },
                x: {
                    ticks: {
                        font: { size: 11, family: "'Sarabun', sans-serif" },
                        maxRotation: 45,
                        minRotation: 45
                    },
                    grid: { display: false }
                }
            }
        };

        if (ntAreaChart) {
            ntAreaChart.data = chartData;
            ntAreaChart.options = options;
            ntAreaChart.update();
        } else {
            ntAreaChart = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: options
            });
        }
    }

    /**
     * Update NT department comparison chart
     */
    function updateNTDeptChart(ntData, deptComparisons) {
        const ctx = document.getElementById('ntDeptChart');
        if (!ctx) return;

        const labels = [];
        const mathData = [];
        const thaiData = [];

        Object.keys(deptComparisons).forEach(dept => {
            const deptData = deptComparisons[dept];
            if (deptData.nt_count > 0) {
                labels.push(dept);
                mathData.push(deptData.nt_math / deptData.nt_count);
                thaiData.push(deptData.nt_thai / deptData.nt_count);
            }
        });

        const chartData = {
            labels: labels,
            datasets: [
                {
                    label: 'คณิตศาสตร์',
                    data: mathData,
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    borderRadius: 6
                },
                {
                    label: 'ภาษาไทย',
                    data: thaiData,
                    backgroundColor: 'rgba(99, 179, 237, 0.8)',
                    borderColor: 'rgba(99, 179, 237, 1)',
                    borderWidth: 2,
                    borderRadius: 6
                }
            ]
        };

        const options = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: { size: 12, family: "'Sarabun', sans-serif" },
                        padding: 10
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 13, family: "'Sarabun', sans-serif" },
                    bodyFont: { size: 12, family: "'Sarabun', sans-serif" },
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + ' คะแนน';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        font: { size: 11, family: "'Sarabun', sans-serif" }
                    },
                    grid: { color: 'rgba(0, 0, 0, 0.05)' }
                },
                x: {
                    ticks: {
                        font: { size: 10, family: "'Sarabun', sans-serif" },
                        maxRotation: 45,
                        minRotation: 45
                    },
                    grid: { display: false }
                }
            }
        };

        if (ntDeptChart) {
            ntDeptChart.data = chartData;
            ntDeptChart.options = options;
            ntDeptChart.update();
        } else {
            ntDeptChart = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: options
            });
        }
    }

    /**
     * Update RT stat cards
     */
    function updateRTStatCards(rtData) {
        const readingAvg = document.getElementById('rtReadingAvg');
        const compAvg = document.getElementById('rtCompAvg');
        
        if (readingAvg) readingAvg.textContent = rtData.reading ? rtData.reading.toFixed(2) : '0';
        if (compAvg) compAvg.textContent = rtData.comprehension ? rtData.comprehension.toFixed(2) : '0';
    }

    /**
     * Update RT area comparison chart
     */
    function updateRTAreaChart(rtData, areaComparisons) {
        const ctx = document.getElementById('rtAreaChart');
        if (!ctx) return;

        const labels = [];
        const readingData = [];
        const compData = [];

        Object.keys(areaComparisons).forEach(area => {
            const areaData = areaComparisons[area];
            if (areaData.rt_count > 0) {
                labels.push(area);
                readingData.push(areaData.rt_reading / areaData.rt_count);
                compData.push(areaData.rt_comprehension / areaData.rt_count);
            }
        });

        const chartData = {
            labels: labels,
            datasets: [
                {
                    label: 'การอ่านออกเสียง',
                    data: readingData,
                    backgroundColor: 'rgba(255, 206, 86, 0.8)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 2,
                    borderRadius: 6
                },
                {
                    label: 'การอ่านรู้เรื่อง',
                    data: compData,
                    backgroundColor: 'rgba(255, 223, 128, 0.8)',
                    borderColor: 'rgba(255, 223, 128, 1)',
                    borderWidth: 2,
                    borderRadius: 6
                }
            ]
        };

        const options = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: { size: 12, family: "'Sarabun', sans-serif" },
                        padding: 10
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 13, family: "'Sarabun', sans-serif" },
                    bodyFont: { size: 12, family: "'Sarabun', sans-serif" },
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + ' คะแนน';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        font: { size: 11, family: "'Sarabun', sans-serif" }
                    },
                    grid: { color: 'rgba(0, 0, 0, 0.05)' }
                },
                x: {
                    ticks: {
                        font: { size: 11, family: "'Sarabun', sans-serif" },
                        maxRotation: 45,
                        minRotation: 45
                    },
                    grid: { display: false }
                }
            }
        };

        if (rtAreaChart) {
            rtAreaChart.data = chartData;
            rtAreaChart.options = options;
            rtAreaChart.update();
        } else {
            rtAreaChart = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: options
            });
        }
    }

    /**
     * Update RT department comparison chart
     */
    function updateRTDeptChart(rtData, deptComparisons) {
        const ctx = document.getElementById('rtDeptChart');
        if (!ctx) return;

        const labels = [];
        const readingData = [];
        const compData = [];

        Object.keys(deptComparisons).forEach(dept => {
            const deptData = deptComparisons[dept];
            if (deptData.rt_count > 0) {
                labels.push(dept);
                readingData.push(deptData.rt_reading / deptData.rt_count);
                compData.push(deptData.rt_comprehension / deptData.rt_count);
            }
        });

        const chartData = {
            labels: labels,
            datasets: [
                {
                    label: 'การอ่านออกเสียง',
                    data: readingData,
                    backgroundColor: 'rgba(255, 206, 86, 0.8)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 2,
                    borderRadius: 6
                },
                {
                    label: 'การอ่านรู้เรื่อง',
                    data: compData,
                    backgroundColor: 'rgba(255, 223, 128, 0.8)',
                    borderColor: 'rgba(255, 223, 128, 1)',
                    borderWidth: 2,
                    borderRadius: 6
                }
            ]
        };

        const options = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: { size: 12, family: "'Sarabun', sans-serif" },
                        padding: 10
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 13, family: "'Sarabun', sans-serif" },
                    bodyFont: { size: 12, family: "'Sarabun', sans-serif" },
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + ' คะแนน';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        font: { size: 11, family: "'Sarabun', sans-serif" }
                    },
                    grid: { color: 'rgba(0, 0, 0, 0.05)' }
                },
                x: {
                    ticks: {
                        font: { size: 10, family: "'Sarabun', sans-serif" },
                        maxRotation: 45,
                        minRotation: 45
                    },
                    grid: { display: false }
                }
            }
        };

        if (rtDeptChart) {
            rtDeptChart.data = chartData;
            rtDeptChart.options = options;
            rtDeptChart.update();
        } else {
            rtDeptChart = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: options
            });
        }
    }

    /**
     * Update O-NET area comparison chart
     */
    function updateONETAreaChart(onetData, areaComparisons) {
        const ctx = document.getElementById('onetAreaChart');
        if (!ctx) return;

        const labels = [];
        const mathData = [];
        const thaiData = [];
        const englishData = [];
        const scienceData = [];

        Object.keys(areaComparisons).forEach(area => {
            const areaData = areaComparisons[area];
            if (areaData.onet_count > 0) {
                labels.push(area);
                mathData.push(areaData.onet_math / areaData.onet_count);
                thaiData.push(areaData.onet_thai / areaData.onet_count);
                englishData.push(areaData.onet_english / areaData.onet_count);
                scienceData.push(areaData.onet_science / areaData.onet_count);
            }
        });

        const chartData = {
            labels: labels,
            datasets: [
                {
                    label: 'คณิตศาสตร์',
                    data: mathData,
                    backgroundColor: 'rgba(75, 192, 192, 0.8)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    borderRadius: 6
                },
                {
                    label: 'ภาษาไทย',
                    data: thaiData,
                    backgroundColor: 'rgba(102, 204, 204, 0.8)',
                    borderColor: 'rgba(102, 204, 204, 1)',
                    borderWidth: 2,
                    borderRadius: 6
                },
                {
                    label: 'ภาษาอังกฤษ',
                    data: englishData,
                    backgroundColor: 'rgba(128, 216, 216, 0.8)',
                    borderColor: 'rgba(128, 216, 216, 1)',
                    borderWidth: 2,
                    borderRadius: 6
                },
                {
                    label: 'วิทยาศาสตร์',
                    data: scienceData,
                    backgroundColor: 'rgba(153, 228, 228, 0.8)',
                    borderColor: 'rgba(153, 228, 228, 1)',
                    borderWidth: 2,
                    borderRadius: 6
                }
            ]
        };

        const options = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: { size: 12, family: "'Sarabun', sans-serif" },
                        padding: 10
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 13, family: "'Sarabun', sans-serif" },
                    bodyFont: { size: 12, family: "'Sarabun', sans-serif" },
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + ' คะแนน';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        font: { size: 11, family: "'Sarabun', sans-serif" }
                    },
                    grid: { color: 'rgba(0, 0, 0, 0.05)' }
                },
                x: {
                    ticks: {
                        font: { size: 11, family: "'Sarabun', sans-serif" },
                        maxRotation: 45,
                        minRotation: 45
                    },
                    grid: { display: false }
                }
            }
        };

        if (onetAreaChart) {
            onetAreaChart.data = chartData;
            onetAreaChart.options = options;
            onetAreaChart.update();
        } else {
            onetAreaChart = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: options
            });
        }
    }

    /**
     * Update O-NET area ranking table
     */
    function updateONETAreaTable(onetData, areaComparisons) {
        const container = document.getElementById('onetAreaTable');
        if (!container) return;

        // Calculate averages for each area and subject
        const areaData = [];
        Object.keys(areaComparisons).forEach(area => {
            const data = areaComparisons[area];
            if (data.onet_count > 0) {
                areaData.push({
                    name: area,
                    math: data.onet_math / data.onet_count,
                    thai: data.onet_thai / data.onet_count,
                    english: data.onet_english / data.onet_count,
                    science: data.onet_science / data.onet_count,
                    count: data.onet_count
                });
            }
        });

        // Build table HTML
        let html = '<table style="width: 100%; font-size: 13px; border-collapse: collapse;">';
        html += '<thead style="background: #f7fafc; border-bottom: 2px solid #e2e8f0;">';
        html += '<tr><th style="padding: 10px; text-align: left; font-weight: 600;">วิชา</th>';
        html += '<th style="padding: 10px; text-align: left; font-weight: 600;">อำเภอ</th>';
        html += '<th style="padding: 10px; text-align: center; font-weight: 600;">คะแนน</th></tr></thead>';
        html += '<tbody>';

        // Top 4 for each subject
        const subjects = [
            { key: 'math', label: 'คณิตศาสตร์', color: '#4bc0c0' },
            { key: 'thai', label: 'ภาษาไทย', color: '#66cccc' },
            { key: 'english', label: 'ภาษาอังกฤษ', color: '#80d8d8' },
            { key: 'science', label: 'วิทยาศาสตร์', color: '#99e4e4' }
        ];

        subjects.forEach(subject => {
            const sorted = [...areaData].sort((a, b) => b[subject.key] - a[subject.key]).slice(0, 4);
            sorted.forEach((item, index) => {
                if (index === 0) {
                    html += `<tr style="border-bottom: 1px solid #f0f0f0;">`;
                    html += `<td rowspan="4" style="padding: 10px; vertical-align: top; border-right: 3px solid ${subject.color}; font-weight: 600;">${subject.label}</td>`;
                } else {
                    html += `<tr style="border-bottom: 1px solid #f0f0f0;">`;
                }
                html += `<td style="padding: 8px;">${index + 1}. ${item.name}</td>`;
                html += `<td style="padding: 8px; text-align: center; font-weight: 600; color: ${subject.color};">${item[subject.key].toFixed(2)}</td>`;
                html += '</tr>';
            });
        });

        html += '</tbody></table>';
        container.innerHTML = html;
    }

    /**
     * Update O-NET department ranking table
     */
    function updateONETDeptTable(onetData, deptComparisons) {
        const container = document.getElementById('onetDeptTable');
        if (!container) return;

        // Calculate averages for each department and subject
        const deptData = [];
        Object.keys(deptComparisons).forEach(dept => {
            const data = deptComparisons[dept];
            if (data.onet_count > 0) {
                deptData.push({
                    name: dept,
                    math: data.onet_math / data.onet_count,
                    thai: data.onet_thai / data.onet_count,
                    english: data.onet_english / data.onet_count,
                    science: data.onet_science / data.onet_count,
                    count: data.onet_count
                });
            }
        });

        // Build table HTML
        let html = '<table style="width: 100%; font-size: 13px; border-collapse: collapse;">';
        html += '<thead style="background: #f7fafc; border-bottom: 2px solid #e2e8f0;">';
        html += '<tr><th style="padding: 10px; text-align: left; font-weight: 600;">วิชา</th>';
        html += '<th style="padding: 10px; text-align: left; font-weight: 600;">สังกัด</th>';
        html += '<th style="padding: 10px; text-align: center; font-weight: 600;">คะแนน</th></tr></thead>';
        html += '<tbody>';

        // Top 4 for each subject
        const subjects = [
            { key: 'math', label: 'คณิตศาสตร์', color: '#4bc0c0' },
            { key: 'thai', label: 'ภาษาไทย', color: '#66cccc' },
            { key: 'english', label: 'ภาษาอังกฤษ', color: '#80d8d8' },
            { key: 'science', label: 'วิทยาศาสตร์', color: '#99e4e4' }
        ];

        subjects.forEach(subject => {
            const sorted = [...deptData].sort((a, b) => b[subject.key] - a[subject.key]).slice(0, 4);
            sorted.forEach((item, index) => {
                if (index === 0) {
                    html += `<tr style="border-bottom: 1px solid #f0f0f0;">`;
                    html += `<td rowspan="4" style="padding: 10px; vertical-align: top; border-right: 3px solid ${subject.color}; font-weight: 600;">${subject.label}</td>`;
                } else {
                    html += `<tr style="border-bottom: 1px solid #f0f0f0;">`;
                }
                html += `<td style="padding: 8px;">${index + 1}. ${item.name}</td>`;
                html += `<td style="padding: 8px; text-align: center; font-weight: 600; color: ${subject.color};">${item[subject.key].toFixed(2)}</td>`;
                html += '</tr>';
            });
        });

        html += '</tbody></table>';
        container.innerHTML = html;
    }

    /**
     * Update statistics cards
     */
    function updateStatistics(stats) {
        // Update stat cards if they exist
        const totalSchoolsEl = document.getElementById('totalSchools');
        const totalScoresEl = document.getElementById('totalScores');
        const ntAvgEl = document.getElementById('ntAvg');
        const rtAvgEl = document.getElementById('rtAvg');
        const onetAvgEl = document.getElementById('onetAvg');

        if (totalSchoolsEl) totalSchoolsEl.textContent = stats.total_schools.toLocaleString();
        if (totalScoresEl) totalScoresEl.textContent = stats.total_scores.toLocaleString();
        
        // Calculate and display averages
        if (ntAvgEl) {
            const ntAvg = stats.nt_count > 0 ? (stats.nt_total / stats.nt_count).toFixed(2) : '0';
            ntAvgEl.textContent = ntAvg;
        }
        if (rtAvgEl) {
            const rtAvg = stats.rt_count > 0 ? (stats.rt_total / stats.rt_count).toFixed(2) : '0';
            rtAvgEl.textContent = rtAvg;
        }
        if (onetAvgEl) {
            const onetAvg = stats.onet_count > 0 ? (stats.onet_total / stats.onet_count).toFixed(2) : '0';
            onetAvgEl.textContent = onetAvg;
        }
    }

    /**
     * Setup filter handlers
     */
    function setupFilterHandlers() {
        // Year filter
        const yearFilter = document.getElementById('yearFilter');
        if (yearFilter) {
            yearFilter.addEventListener('change', function() {
                currentFilters.year = parseInt(this.value);
                loadChartData();
            });
        }

        // Department filter
        const departmentFilter = document.getElementById('departmentFilter');
        if (departmentFilter) {
            departmentFilter.addEventListener('change', function() {
                currentFilters.department = this.value;
                loadChartData();
            });
        }

        // Refresh button
        const refreshBtn = document.getElementById('refreshCharts');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                loadChartData();
            });
        }
    }

    /**
     * Buddhist year helper
     */
    function toBuddhistYear(ceYear) {
        return ceYear + 543;
    }

    /**
     * Handle window resize for responsive charts
     */
    let resizeTimer;
    function handleResize() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Reload charts with new responsive settings
            if (ntAreaChart) ntAreaChart.destroy();
            if (ntDeptChart) ntDeptChart.destroy();
            if (rtAreaChart) rtAreaChart.destroy();
            if (rtDeptChart) rtDeptChart.destroy();
            if (onetAreaChart) onetAreaChart.destroy();
            
            ntAreaChart = null;
            ntDeptChart = null;
            rtAreaChart = null;
            rtDeptChart = null;
            onetAreaChart = null;
            
            loadChartData();
        }, 250);
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCharts);
    } else {
        initCharts();
    }

    // Add resize listener for responsive updates
    window.addEventListener('resize', handleResize);

    // Export for external use if needed
    window.AcademicCharts = {
        refresh: loadChartData,
        setFilters: function(filters) {
            Object.assign(currentFilters, filters);
            loadChartData();
        }
    };

})();
