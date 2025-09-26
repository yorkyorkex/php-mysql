class CourseReportApp {
    constructor() {
        this.currentPage = 1;
        this.recordsPerPage = 50;
        this.filters = {
            user_name: '',
            course_name: '',
            status: ''
        };
        this.totalPages = 1;
        this.isLoading = false;
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.loadStatistics();
        this.loadEnrolmentData();
    }
    
    bindEvents() {
        // Filter events
        document.getElementById('user-search').addEventListener('input', 
            this.debounce((e) => this.handleFilterChange('user_name', e.target.value), 500)
        );
        
        document.getElementById('course-search').addEventListener('input', 
            this.debounce((e) => this.handleFilterChange('course_name', e.target.value), 500)
        );
        
        document.getElementById('status-filter').addEventListener('change', 
            (e) => this.handleFilterChange('status', e.target.value)
        );
        
        document.getElementById('clear-filters').addEventListener('click', 
            () => this.clearFilters()
        );
        
        // Pagination events
        document.getElementById('prev-page').addEventListener('click', 
            () => this.changePage(this.currentPage - 1)
        );
        
        document.getElementById('next-page').addEventListener('click', 
            () => this.changePage(this.currentPage + 1)
        );
        
        // Records per page change
        document.getElementById('records-per-page').addEventListener('change', 
            (e) => this.changeRecordsPerPage(parseInt(e.target.value))
        );
    }
    
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    async loadStatistics() {
        try {
            const response = await fetch('./api/index.php?action=statistics');
            const result = await response.json();
            
            if (result.success) {
                this.updateStatistics(result.data);
            } else {
                this.showError('Failed to load statistics: ' + result.error);
            }
        } catch (error) {
            this.showError('Error loading statistics: ' + error.message);
        }
    }
    
    updateStatistics(stats) {
        document.getElementById('total-enrolments').textContent = 
            this.formatNumber(stats.total_enrolments);
        document.getElementById('total-users').textContent = 
            this.formatNumber(stats.total_users);
        document.getElementById('total-courses').textContent = 
            this.formatNumber(stats.total_courses);
        document.getElementById('completion-rate').textContent = 
            stats.completion_rate + '%';
    }
    
    async loadEnrolmentData() {
        if (this.isLoading) return;
        
        this.isLoading = true;
        this.showLoading(true);
        this.hideError();
        
        try {
            const params = new URLSearchParams({
                action: 'enrolments',
                page: this.currentPage,
                limit: this.recordsPerPage,
                ...this.filters
            });
            
            const response = await fetch(`./api/index.php?${params}`);
            const result = await response.json();
            
            if (result.success) {
                this.updateTable(result.data);
                this.updatePagination(result.pagination);
                this.updateTableInfo(result.pagination);
            } else {
                this.showError('Failed to load data: ' + result.error);
            }
        } catch (error) {
            this.showError('Error loading data: ' + error.message);
        } finally {
            this.isLoading = false;
            this.showLoading(false);
        }
    }
    
    updateTable(data) {
        const tbody = document.getElementById('table-body');
        tbody.innerHTML = '';
        
        if (data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: #666;">
                        <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                        No records found
                    </td>
                </tr>
            `;
            return;
        }
        
        data.forEach(record => {
            const row = document.createElement('tr');
            row.className = 'fade-in';
            row.innerHTML = `
                <td>
                    <strong>${this.escapeHtml(record.first_name)} ${this.escapeHtml(record.surname)}</strong>
                </td>
                <td>${this.escapeHtml(record.course_description)}</td>
                <td>
                    <span class="status-badge status-${record.completion_status.replace(' ', '-')}">
                        ${this.capitalizeStatus(record.completion_status)}
                    </span>
                </td>
                <td>${this.formatDate(record.enrolled_at)}</td>
                <td>${record.completed_at ? this.formatDate(record.completed_at) : '-'}</td>
            `;
            tbody.appendChild(row);
        });
    }
    
    updatePagination(pagination) {
        this.currentPage = pagination.current_page;
        this.totalPages = pagination.total_pages;
        
        document.getElementById('page-info').textContent = 
            `Page ${pagination.current_page} of ${pagination.total_pages}`;
        
        document.getElementById('prev-page').disabled = pagination.current_page <= 1;
        document.getElementById('next-page').disabled = pagination.current_page >= pagination.total_pages;
    }
    
    updateTableInfo(pagination) {
        const start = (pagination.current_page - 1) * pagination.records_per_page + 1;
        const end = Math.min(start + pagination.records_per_page - 1, pagination.total_records);
        
        document.getElementById('table-info').textContent = 
            `Showing ${this.formatNumber(start)}-${this.formatNumber(end)} of ${this.formatNumber(pagination.total_records)} records`;
    }
    
    handleFilterChange(filterName, value) {
        this.filters[filterName] = value;
        this.currentPage = 1;
        this.loadEnrolmentData();
    }
    
    clearFilters() {
        this.filters = {
            user_name: '',
            course_name: '',
            status: ''
        };
        
        document.getElementById('user-search').value = '';
        document.getElementById('course-search').value = '';
        document.getElementById('status-filter').value = '';
        
        this.currentPage = 1;
        this.loadEnrolmentData();
    }
    
    changePage(page) {
        if (page < 1 || page > this.totalPages || page === this.currentPage) {
            return;
        }
        
        this.currentPage = page;
        this.loadEnrolmentData();
        
        // Scroll to top of table
        document.querySelector('.table-section').scrollIntoView({ 
            behavior: 'smooth' 
        });
    }
    
    changeRecordsPerPage(newLimit) {
        this.recordsPerPage = newLimit;
        this.currentPage = 1;
        this.loadEnrolmentData();
    }
    
    showLoading(show) {
        const loadingElement = document.getElementById('loading');
        loadingElement.style.display = show ? 'block' : 'none';
    }
    
    showError(message) {
        const errorElement = document.getElementById('error-message');
        const errorText = document.getElementById('error-text');
        
        errorText.textContent = message;
        errorElement.style.display = 'block';
        
        // Auto-hide error after 5 seconds
        setTimeout(() => {
            this.hideError();
        }, 5000);
    }
    
    hideError() {
        document.getElementById('error-message').style.display = 'none';
    }
    
    formatNumber(num) {
        return new Intl.NumberFormat().format(num);
    }
    
    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }
    
    capitalizeStatus(status) {
        return status.split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Enhanced error handling for API failures
window.addEventListener('unhandledrejection', function(event) {
    console.error('Unhandled promise rejection:', event.reason);
    // You could show a user-friendly error message here
});

// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Add loading animation to stats cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('fade-in');
        }, index * 100);
    });
    
    // Initialize the main application
    new CourseReportApp();
});

// Add keyboard navigation support
document.addEventListener('keydown', (e) => {
    // Allow Enter key to trigger search
    if (e.key === 'Enter' && (e.target.id === 'user-search' || e.target.id === 'course-search')) {
        e.target.blur(); // This will trigger the input event and start search
    }
});

// Add smooth scrolling for better UX
document.documentElement.style.scrollBehavior = 'smooth';

// Performance optimization: Intersection Observer for lazy loading
if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    });
    
    // Observe table rows for smooth animations
    setTimeout(() => {
        document.querySelectorAll('#enrolments-table tbody tr').forEach(row => {
            observer.observe(row);
        });
    }, 100);
}