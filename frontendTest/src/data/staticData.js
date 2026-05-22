export const menuItems = [
  { id: 'dashboard', label: 'Tableau de bord', icon: 'fas fa-tachometer-alt' },
  { id: 'maison', label: 'Maison', icon: 'fas fa-home' },
  { id: 'adhesion', label: 'Mon adhésion', icon: 'fas fa-id-card' },
  { id: 'boutique', label: 'Boutique', icon: 'fas fa-store' },
  { id: 'mesinfos', label: 'Mes informations', icon: 'fas fa-user-circle' },
  { id: 'commandes', label: 'Commandes', icon: 'fas fa-shopping-cart' },
  { id: 'equipe', label: 'Mon équipe', icon: 'fas fa-users' },
  { id: 'recommandations', label: 'Recommandations', icon: 'fas fa-user-plus' },
  { id: 'recruteur', label: 'Mon recruteur', icon: 'fas fa-user-tie' },
  { id: 'classement', label: 'Classement', icon: 'fas fa-trophy' },
  { id: 'sitesweb', label: 'Mes sites web', icon: 'fas fa-globe' },
  { id: 'webinaires', label: 'Webinaires', icon: 'fas fa-chalkboard-user' },
  { id: 'statistiques', label: 'Statistiques', icon: 'fas fa-chart-line' },
  { id: 'planremuneration', label: 'Plan de rémunération', icon: 'fas fa-file-invoice-dollar' },
  { id: 'gains', label: 'Mes gains', icon: 'fas fa-coins' },
  { id: 'contact', label: 'Contact', icon: 'fas fa-headset' },
  { id: 'logout', label: 'Déconnexion', icon: 'fas fa-sign-out-alt' }
]

export const recentUsersData = [
  { name: 'Ngab sauveur', time: '06:21:32' },
  { name: 'Lebohang Sha', time: '06:21:31' },
  { name: 'Noël Cueva', time: '06:21:23' },
  { name: 'BERNARD EMAN', time: '06:21:23' }
]

export const productsData = [
  { name: 'Multi-Vitamine Complète', desc: '24 vitamines et minéraux', price: '9,95€', oldPrice: '17,95€', image: 'https://placehold.co/400x200/3b82f6/white?text=Multivitamine' },
  { name: 'Complexe Ultra Magnésium', desc: 'Soutien musculaire', price: '9,95€', oldPrice: '17,95€', image: 'https://placehold.co/400x200/10b981/white?text=Magn%C3%A9sium' },
  { name: 'Protéine Végétale', desc: 'Reconstruction musculaire', price: '14,95€', oldPrice: '24,95€', image: 'https://placehold.co/400x200/8b5cf6/white?text=Prot%C3%A9ine' }
]

export const ordersData = [
  { date: '15/02/2025', product: 'Multi-Vitamine', amount: '9,95€', status: 'Livrée', statusClass: 'text-green-600' },
  { date: '01/02/2025', product: 'Magnésium Ultra', amount: '9,95€', status: 'Livrée', statusClass: 'text-green-600' },
  { date: '20/01/2025', product: 'Protéine Végétale', amount: '14,95€', status: 'En cours', statusClass: 'text-amber-600' }
]

export const recommendationsData = [
  { nom: 'Sophie Bernard', email: 'sophie@email.com', status: 'Actif ✓', statusClass: 'text-green-600' },
  { nom: 'Marc Laurent', email: 'marc@email.com', status: 'Actif ✓', statusClass: 'text-green-600' },
  { nom: 'Julie Martin', email: 'julie@email.com', status: 'Inactif', statusClass: 'text-red-600' }
]

export const leadersData = [
  { nom: 'Jeffrey Aman', membres: 475 },
  { nom: 'Michael Prikazsky', membres: 238 },
  { nom: 'Erik Johnson', membres: 222 },
  { nom: 'Edward Keyte', membres: 201 },
  { nom: 'Joshua Igini', membres: 153 },
  { nom: 'Angela Holmes', membres: 145 }
]

export const sitesWebData = [
  { type: 'Corporatif', url: 'https://LiveGood.com/yohann76', icon: 'fas fa-building', cardClass: 'bg-white border border-gray-200', badgeClass: 'bg-blue-100 text-blue-700' },
  { type: 'Détail', url: 'https://ShopLiveGood.com/yohann76', icon: 'fas fa-tag', cardClass: 'bg-white border border-gray-200', badgeClass: 'bg-green-100 text-green-700' },
  { type: 'Inscription', url: 'https://LiveGoodTour.com/yohann76', icon: 'fas fa-user-plus', cardClass: 'bg-gradient-to-br from-purple-50 to-indigo-50 border border-purple-200', badgeClass: 'bg-purple-200 text-purple-800' }
]

export const webinairesData = [
  { jour: 'Jeudi', horaire: '20h', langue: 'FR', titre: 'Co-fondateurs', icon: 'fas fa-chalkboard-user', iconColor: 'blue', titleClass: 'text-blue-600 font-semibold' },
  { jour: 'Dimanche', horaire: '18h30', langue: 'EN', titre: 'Formation produits', icon: 'fas fa-chalkboard-user', iconColor: 'green', titleClass: 'text-gray-600' },
  { jour: 'Vendredi', horaire: '21h', langue: 'EN', titre: 'Grands leaders', icon: 'fas fa-chalkboard-user', iconColor: 'purple', titleClass: 'text-gray-600' }
]

export const statsSitesData = [
  { nom: 'Site corporatif', valeur: '1,247 visites', trend: '+12%', trendClass: 'text-green-600', icon: 'fas fa-globe text-blue-500' },
  { nom: 'Vente au détail', valeur: '856 visites', trend: '8 commandes', trendClass: 'text-amber-600', icon: 'fas fa-tag text-green-500' },
  { nom: 'Page inscription', valeur: '2,103 visites', trend: '45 pré-inscrits', trendClass: 'text-green-600', icon: 'fas fa-user-plus text-purple-500' }
]
