/**
 * Date Filter JavaScript
 * Handles quick date range selection
 */

function setDateRange(range) {
    const today = new Date();
    let fromDate, toDate;

    switch (range) {
        case 'today':
            fromDate = toDate = formatDate(today);
            break;

        case 'yesterday':
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            fromDate = toDate = formatDate(yesterday);
            break;

        case 'this_week':
            const weekStart = new Date(today);
            weekStart.setDate(today.getDate() - today.getDay());
            fromDate = formatDate(weekStart);
            toDate = formatDate(today);
            break;

        case 'last_week':
            const lastWeekStart = new Date(today);
            lastWeekStart.setDate(today.getDate() - today.getDay() - 7);
            const lastWeekEnd = new Date(lastWeekStart);
            lastWeekEnd.setDate(lastWeekStart.getDate() + 6);
            fromDate = formatDate(lastWeekStart);
            toDate = formatDate(lastWeekEnd);
            break;

        case 'this_month':
            fromDate = formatDate(new Date(today.getFullYear(), today.getMonth(), 1));
            toDate = formatDate(today);
            break;

        case 'last_month':
            const lastMonthStart = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            const lastMonthEnd = new Date(today.getFullYear(), today.getMonth(), 0);
            fromDate = formatDate(lastMonthStart);
            toDate = formatDate(lastMonthEnd);
            break;

        case 'this_year':
            fromDate = formatDate(new Date(today.getFullYear(), 0, 1));
            toDate = formatDate(today);
            break;
    }

    // Set the values
    document.querySelector('input[name="date_from"]').value = fromDate;
    document.querySelector('input[name="date_to"]').value = toDate;

    // Auto submit the form
    document.getElementById('date-filter-form').submit();
}

function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function exportResults(exportRoute) {
    if (!exportRoute) {
        alert('Export not available for this page');
        return;
    }

    // Get current filter values
    const dateFrom = document.querySelector('input[name="date_from"]')?.value || '';
    const dateTo = document.querySelector('input[name="date_to"]')?.value || '';
    const status = document.querySelector('select[name="status"]')?.value || '';
    const paymentStatus = document.querySelector('select[name="payment_status"]')?.value || '';

    // Build export URL with filters
    let url = exportRoute + '?';
    if (dateFrom) url += `date_from=${dateFrom}&`;
    if (dateTo) url += `date_to=${dateTo}&`;
    if (status) url += `status=${status}&`;
    if (paymentStatus) url += `payment_status=${paymentStatus}&`;

    window.location.href = url;
}