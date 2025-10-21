<script setup>
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    user: {
        type: Object,
        default: null
    }
});

const isAdmin = computed(() => {
    return props.user?.role === 'admin';
});

const fullName = computed(() => {
    if (!props.user) return '';
    return `${props.user.first_name} ${props.user.last_name}`;
});

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="modern-sidebar">
        <!-- Logo Section -->
        <div class="sidebar-header">
            <div class="logo-container">
                <div class="logo-icon">
                   <a href="/" title="KAMENIKA"><img style="border-radius: 25%;" src="https://i.yapx.ru/a4fI0.jpg" alt="KAMENIKA"></a>
                </div>
                <span class="logo-text">KAMENIKA</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <div class="nav-section">
                <h3 class="nav-section-title">Меню</h3>
                <Link href="/" class="nav-item">
                    <div class="nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9,22 9,12 15,12 15,22"/>
                        </svg>
                    </div>
                    <span class="nav-text">Главная</span>
                </Link>
                
                <Link href="/recipes" class="nav-item">
                    <div class="nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gem" viewBox="0 0 16 16">
  <path d="M3.1.7a.5.5 0 0 1 .4-.2h9a.5.5 0 0 1 .4.2l2.976 3.974c.149.185.156.45.01.644L8.4 15.3a.5.5 0 0 1-.8 0L.1 5.3a.5.5 0 0 1 0-.6l3-4zm11.386 3.785-1.806-2.41-.776 2.413 2.582-.003zm-3.633.004.961-2.989H4.186l.963 2.995 5.704-.006zM5.47 5.495 8 13.366l2.532-7.876-5.062.005zm-1.371-.999-.78-2.422-1.818 2.425 2.598-.003zM1.499 5.5l5.113 6.817-2.192-6.82L1.5 5.5zm7.889 6.817 5.123-6.83-2.928.002-2.195 6.828z"/>
</svg>
                    </div>
                    <span class="nav-text">Изделия</span>
                </Link>
                
                
                <Link href="/categories" class="nav-item">
                    <div class="nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <rect x="3" y="3" width="7" height="7"/>
                            <rect x="14" y="3" width="7" height="7"/>
                            <rect x="14" y="14" width="7" height="7"/>
                            <rect x="3" y="14" width="7" height="7"/>
                        </svg>
                    </div>
                    <span class="nav-text">Категории</span>
                </Link>
                
                <Link href="/articles" class="nav-item">
                    <div class="nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-book" viewBox="0 0 16 16">
  <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
</svg>
                    </div>
                    <span class="nav-text">Статьи</span>
                </Link>
                
            </div>

            <!-- User Section -->
            <div class="nav-section">
                <template v-if="user">
                    <h3 class="nav-section-title">Личное</h3>
                    
                    <Link href="/favorites" class="nav-item">
                        <div class="nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                        </div>
                        <span class="nav-text">Избранное</span>
                    </Link>

                    <Link href="/cart" class="nav-item">
                    <div class="nav-icon">
                       <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
  <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
</svg>
                    </div>
                    <span class="nav-text">Корзина</span>
                </Link>
                </template>
                
                <template v-else>
                    <h3 class="nav-section-title">Аккаунт</h3>
                    
                    <Link href="/login" class="nav-item">
                        <div class="nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                <polyline points="10,17 15,12 10,7"/>
                                <line x1="15" y1="12" x2="3" y2="12"/>
                            </svg>
                        </div>
                        <span class="nav-text">Вход</span>
                    </Link>
                    
                    <Link href="/register" class="nav-item">
                        <div class="nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="8.5" cy="7" r="4"/>
                                <line x1="20" y1="8" x2="20" y2="14"/>
                                <line x1="23" y1="11" x2="17" y2="11"/>
                            </svg>
                        </div>
                        <span class="nav-text">Регистрация</span>
                    </Link>
                </template>
            </div>
        </nav>

        <!-- User Profile Section -->
        <div v-if="user" class="sidebar-footer">
            <Link :href="route('profile.show')" class="profile-section">
                <div class="profile-avatar-container">
                    <img 
                        :src="user.avatar_url || '/storage/images/logo.png'" 
                        :alt="fullName"
                        class="profile-avatar"
                        @error="$event.target.src = '/storage/images/logo.png'"
                    />
                    <div class="online-indicator"></div>
                </div>
                <div class="profile-info">
                    <span class="profile-name">{{ fullName }}</span>
                    <span class="profile-role">{{ user.role === 'admin' ? 'Администратор' : 'Пользователь' }}</span>
                </div>
            </Link>
            
            <button @click="logout" class="logout-button">
                <div class="nav-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16,17 21,12 16,7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </div>
                <span class="nav-text">Выход</span>
            </button>
        </div>
    </div>
</template>

<style scoped>
.modern-sidebar {
    width: 280px;
    height: 100vh;
    background: linear-gradient(180deg, #1a202c 0%, #2d3748 100%);
    color: white;
    position: fixed;
    left: 0;
    top: 0;
    display: flex;
    flex-direction: column;
    box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
    z-index: 100;
    padding: 1.5rem 0;
}

/* Header */
.sidebar-header {
    padding: 0 1.5rem 2rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.logo-container {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.logo-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(45deg, #667eea, #764ba2);
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.chef-icon {
    width: 24px;
    height: 24px;
    stroke-width: 2;
    color: white;
}

.logo-text {
    font-size: 1.5rem;
    font-weight: 700;
    background: linear-gradient(45deg, #fff, #e2e8f0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Navigation */
.sidebar-nav {
    flex: 1;
    padding: 2rem 0;
    overflow-y: auto;
}

.nav-section {
    margin-bottom: 2rem;
}

.nav-section-title {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: rgba(255, 255, 255, 0.6);
    margin: 0 1.5rem 1rem;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.5rem;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    margin: 0.25rem 0;
}

.nav-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 0;
    background: linear-gradient(45deg, #667eea, #764ba2);
    border-radius: 0 2px 2px 0;
    transition: height 0.3s ease;
}

.nav-item:hover {
    color: white;
    background: rgba(255, 255, 255, 0.05);
}

.nav-item:hover::before {
    height: 100%;
}

.nav-item.router-link-active {
    color: white;
    background: rgba(102, 126, 234, 0.1);
}

.nav-item.router-link-active::before {
    height: 100%;
}

.nav-icon {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nav-icon svg {
    width: 100%;
    height: 100%;
    stroke-width: 2;
}

.nav-text {
    font-weight: 500;
}

/* Footer */
.sidebar-footer {
    padding: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.profile-section {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    margin-bottom: 1rem;
    text-decoration: none;
    color: white;
    transition: all 0.3s ease;
}

.profile-section:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}

.profile-avatar-container {
    position: relative;
}

.profile-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.online-indicator {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 12px;
    height: 12px;
    background: #48bb78;
    border-radius: 50%;
    border: 2px solid #1a202c;
}

.profile-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.profile-name {
    font-weight: 600;
    font-size: 0.875rem;
    line-height: 1.2;
}

.profile-role {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
}

.logout-button {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    width: 100%;
    padding: 0.75rem 1rem;
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.7);
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.logout-button:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #fc8181;
}

/* Scrollbar */
.sidebar-nav::-webkit-scrollbar {
    width: 4px;
}

.sidebar-nav::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar-nav::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
}

.sidebar-nav::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .modern-sidebar {
        width: 250px;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }
    
    .modern-sidebar.mobile-open {
        transform: translateX(0);
    }
}
</style>