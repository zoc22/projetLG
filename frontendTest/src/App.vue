<template>
  <div :class="['flex h-screen overflow-hidden', darkMode ? 'dark' : '']">
    <!-- Overlay pour mobile -->
    <div 
      v-if="isSidebarOpen" 
      @click="isSidebarOpen = false" 
      class="fixed inset-0 bg-black/50 z-30 lg:hidden transition-opacity"
    ></div>

    <div class="flex h-screen w-full">
      
      <!-- ========== SIDEBAR PREMIUM ========== -->
      <aside :class="[
        'fixed lg:relative inset-y-0 left-0 w-80 bg-white shadow-2xl border-r border-gray-200 flex flex-col z-40 overflow-y-auto scrollbar-custom transition-all duration-300 ease-in-out',
        isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
        !isSidebarDesktopOpen && 'lg:w-20 lg:translate-x-0'
      ]">
        <!-- Logo + Toggle Theme + Toggle Sidebar -->
        <div class="p-4 border-b border-gray-200">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3" :class="{ 'lg:justify-center lg:w-full': !isSidebarDesktopOpen }">
              <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center shadow-lg shrink-0">
                <i class="fas fa-crown text-white text-lg"></i>
              </div>
              <div v-show="isSidebarDesktopOpen" class="transition-opacity" :class="{ 'lg:hidden': !isSidebarDesktopOpen }">
                <span class="font-bold text-xl text-gray-800">Live<span class="text-blue-600">Good</span></span>
                <p class="text-[10px] text-gray-500 mt-0.5">Plateforme Affilié Premium</p>
              </div>
            </div>
            <div class="flex gap-2">
              <button @click="toggleSidebarDesktop" class="hidden lg:flex w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 items-center justify-center transition shrink-0">
                <i :class="[isSidebarDesktopOpen ? 'fas fa-chevron-left' : 'fas fa-chevron-right', 'text-gray-600 text-sm']"></i>
              </button>
              <button @click="toggleDarkMode" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition shrink-0">
                <i v-if="!darkMode" class="fas fa-moon text-gray-600"></i>
                <i v-else class="fas fa-sun text-yellow-500"></i>
              </button>
            </div>
          </div>
        </div>
        
        <!-- Profil utilisateur -->
        <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50" :class="{ 'lg:p-2': !isSidebarDesktopOpen }">
          <div class="flex items-center gap-3" :class="{ 'lg:justify-center': !isSidebarDesktopOpen }">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-md shrink-0">
              <span class="text-white font-bold">YZ</span>
            </div>
            <div v-show="isSidebarDesktopOpen" class="flex-1 min-w-0 transition-opacity">
              <p class="font-bold text-gray-800 text-base truncate">Yohann Zoc</p>
              <p class="text-xs text-blue-600 font-medium">Affilié Bronze</p>
              <p class="text-[10px] text-gray-500 mt-0.5"><i class="fas fa-map-marker-alt"></i> France</p>
            </div>
          </div>
        </div>
        
        <!-- Navigation menu -->
        <nav class="flex-1 py-4 px-3 space-y-1">
          <button v-for="item in menuItems" :key="item.id" @click="showSection(item.id)"
            :class="['sidebar-item w-full text-left px-3 py-2 rounded-xl flex items-center gap-3 transition-all', activeSection === item.id ? 'active' : 'text-gray-700 hover:bg-gray-100', !isSidebarDesktopOpen && 'lg:justify-center']">
            <i :class="[item.icon, 'w-5 text-center shrink-0']"></i>
            <span v-show="isSidebarDesktopOpen" class="transition-opacity">{{ item.label }}</span>
          </button>
        </nav>
        
        <!-- Date limite -->
        <div class="p-3 border-t border-gray-200 bg-red-50 m-3 rounded-xl" :class="{ 'lg:p-2': !isSidebarDesktopOpen }">
          <div class="flex items-center gap-2 mb-1" :class="{ 'lg:justify-center': !isSidebarDesktopOpen }">
            <i class="fas fa-hourglass-half text-red-500 shrink-0"></i>
            <span v-show="isSidebarDesktopOpen" class="text-[10px] font-bold text-red-600">PROCHAINE DATE LIMITE</span>
          </div>
          <p v-show="isSidebarDesktopOpen" class="text-xs font-bold text-gray-800">jeu. 2-mars-2025</p>
          <button class="mt-2 w-full bg-red-500 hover:bg-red-600 text-white py-1.5 rounded-lg text-xs font-semibold transition">
            REGARDER
          </button>
        </div>
      </aside>

      <!-- ========== MAIN CONTENT ========== -->
      <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <header class="bg-white border-b border-gray-200 p-3 flex justify-between items-center">
          <div class="flex items-center gap-3">
            <button @click="isSidebarOpen = true" class="lg:hidden text-gray-600">
              <i class="fas fa-bars text-xl"></i>
            </button>
            <button @click="toggleSidebarDesktop" class="hidden lg:flex items-center gap-2 text-sm text-gray-600 hover:text-gray-800">
              <i :class="[isSidebarDesktopOpen ? 'fas fa-chevron-left' : 'fas fa-chevron-right']"></i>
              <span v-if="!isSidebarDesktopOpen">Menu</span>
            </button>
            <span class="font-bold text-xl text-gray-800 lg:hidden">Live<span class="text-blue-600">Good</span></span>
          </div>
          <div class="flex items-center gap-3">
            <span class="text-sm text-gray-600 hidden lg:block">{{ darkMode ? 'Mode clair' : 'Mode sombre' }}</span>
            <button @click="toggleDarkMode" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition">
              <i v-if="!darkMode" class="fas fa-moon text-gray-600"></i>
              <i v-else class="fas fa-sun text-yellow-500"></i>
            </button>
          </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-6 scrollbar-custom bg-gray-50">
          
          <!-- SECTION TABLEAU DE BORD -->
          <div v-show="activeSection === 'dashboard'" class="section-content">
            <div class="mb-6">
              <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Tableau de bord</h1>
              <p class="text-gray-500 mt-1">Bienvenue Yohann ! Voici l'activité de votre réseau.</p>
            </div>
            
            <!-- Cartes KPI -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
              <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex justify-between items-start">
                  <div><p class="text-gray-500 text-xs">Total membres</p><p class="text-2xl font-bold text-gray-800">128</p></div>
                  <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center"><i class="fas fa-users text-blue-600 text-lg"></i></div>
                </div>
                <p class="text-xs text-green-600 mt-2"><i class="fas fa-arrow-up"></i> +12 cette semaine</p>
              </div>
              <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex justify-between items-start">
                  <div><p class="text-gray-500 text-xs">Membres actifs</p><p class="text-2xl font-bold text-gray-800">96</p></div>
                  <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center"><i class="fas fa-check-circle text-green-600 text-lg"></i></div>
                </div>
                <p class="text-xs text-amber-600 mt-2">32 inactifs à relancer</p>
              </div>
              <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex justify-between items-start">
                  <div><p class="text-gray-500 text-xs">Pré-inscrits (mois)</p><p class="text-2xl font-bold text-gray-800">45</p></div>
                  <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center"><i class="fas fa-hourglass-half text-purple-600 text-lg"></i></div>
                </div>
                <p class="text-xs text-gray-500 mt-2">En attente de paiement</p>
              </div>
              <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="flex justify-between items-start">
                  <div><p class="text-gray-500 text-xs">Gains (semaine)</p><p class="text-2xl font-bold text-gray-800">1,247€</p></div>
                  <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center"><i class="fas fa-euro-sign text-amber-600 text-lg"></i></div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Décalage paiement +7j</p>
              </div>
            </div>
            
            <!-- Graphique avec conteneur à hauteur fixe -->
            <div class="grid lg:grid-cols-2 gap-6">
              <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4">Évolution des gains</h3>
                <!-- Conteneur avec hauteur fixe pour éviter l'extension infinie -->
                <div style="height: 280px; position: relative;">
                  <canvas id="earningsChart"></canvas>
                </div>
              </div>
              <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4"><i class="fas fa-clock text-blue-500"></i> Préinscriptions récentes</h3>
                <div class="space-y-2">
                  <div v-for="user in recentUsers" :key="user.name" class="flex justify-between items-center p-2 hover:bg-gray-50 rounded">
                    <span><i class="fas fa-user text-gray-400"></i> {{ user.name }}</span>
                    <span class="text-xs text-gray-500">{{ user.time }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- SECTION MAISON -->
          <div v-show="activeSection === 'maison'" class="section-content">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Maison d'édition</h1>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
              <div class="grid md:grid-cols-2 gap-6">
                <div><p class="text-gray-500 text-sm">Nom de la maison</p><p class="text-xl font-bold text-gray-800">LiveGood France</p></div>
                <div><p class="text-gray-500 text-sm">Statut</p><span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">Active</span></div>
                <div><p class="text-gray-500 text-sm">Date de création</p><p class="font-semibold text-gray-800">15 Janvier 2024</p></div>
                <div><p class="text-gray-500 text-sm">Total membres</p><p class="font-semibold text-gray-800">128 affiliés</p></div>
              </div>
              <div class="mt-6 p-4 bg-blue-50 rounded-lg"><i class="fas fa-info-circle text-blue-600"></i> <span class="text-gray-700">Votre maison d'édition est certifiée et éligible aux bonus de génération.</span></div>
            </div>
          </div>

          <!-- SECTION ADHÉSION -->
          <div v-show="activeSection === 'adhesion'" class="section-content">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Mon adhésion</h1>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
              <div class="flex justify-between items-center p-4 border-b"><span class="text-gray-600">Forfait actuel</span><span class="font-bold text-blue-600">Forfait Annuel Premium</span></div>
              <div class="flex justify-between items-center p-4 border-b"><span class="text-gray-600">Date d'expiration</span><span class="font-semibold text-gray-800">16 Février 2026</span></div>
              <div class="flex justify-between items-center p-4 border-b"><span class="text-gray-600">Prochain renouvellement</span><span class="font-semibold text-gray-800">99,95 €</span></div>
              <div class="mt-6 p-4 bg-amber-50 rounded-lg"><i class="fas fa-gem text-amber-600"></i> <span class="font-semibold text-gray-700">Upgrade disponible :</span> <span class="text-gray-600">Passez au forfait Diamond pour 199,95€/an</span></div>
              <button class="mt-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition">Upgrader mon compte</button>
            </div>
          </div>

          <!-- SECTION BOUTIQUE -->
          <div v-show="activeSection === 'boutique'" class="section-content">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Boutique LiveGood</h1>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
              <div v-for="product in products" :key="product.name" class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="p-4">
                  <img :src="product.image" class="w-full h-36 object-cover rounded-lg mb-3">
                  <h3 class="font-bold text-gray-800">{{ product.name }}</h3>
                  <p class="text-sm text-gray-500">{{ product.desc }}</p>
                  <div class="flex justify-between items-center mt-3">
                    <span class="text-xl font-bold text-blue-600">{{ product.price }}</span>
                    <span class="text-sm text-gray-400 line-through">{{ product.oldPrice }}</span>
                    <button class="bg-blue-500 text-white px-3 py-1 rounded-lg text-sm">Commander</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- SECTION MES INFORMATIONS -->
          <div v-show="activeSection === 'mesinfos'" class="section-content">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Mes informations</h1>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 max-w-2xl">
              <div class="space-y-4">
                <div><label class="text-gray-500 block text-sm">Nom complet</label><p class="font-semibold text-gray-800">Yohann Zoc</p></div>
                <div><label class="text-gray-500 block text-sm">Email</label><p class="font-semibold text-gray-800">yohann@livegood.com</p></div>
                <div><label class="text-gray-500 block text-sm">Téléphone</label><p class="font-semibold text-gray-800">+33 6 12 34 56 78</p></div>
                <div><label class="text-gray-500 block text-sm">Pays</label><p class="font-semibold text-gray-800">France</p></div>
                <div><label class="text-gray-500 block text-sm">Adresse</label><p class="font-semibold text-gray-800">3 rue de la Paix, 75001 Paris</p></div>
              </div>
              <button class="mt-6 bg-indigo-500 text-white px-6 py-2 rounded-lg">Modifier</button>
            </div>
          </div>

          <!-- SECTION COMMANDES -->
          <div v-show="activeSection === 'commandes'" class="section-content">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Historique des commandes</h1>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 overflow-x-auto">
              <table class="w-full text-left">
                <thead class="bg-gray-50"><tr><th class="p-3 text-gray-700">Date</th><th class="text-gray-700">Produit</th><th class="text-gray-700">Montant</th><th class="text-gray-700">Statut</th></tr></thead>
                <tbody>
                  <tr v-for="order in orders" :key="order.date" class="border-b"><td class="p-3">{{ order.date }}</td><td>{{ order.product }}</td><td>{{ order.amount }}</td><td :class="order.statusClass">{{ order.status }}</td></tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- SECTION MON ÉQUIPE -->
          <div v-show="activeSection === 'equipe'" class="section-content">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Mon équipe</h1>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
              <div class="flex justify-center py-6"><div class="relative"><div class="bg-blue-600 text-white px-6 py-2 rounded-full shadow font-bold text-center mb-6">Yohann Zoc (Bronze)</div><div class="flex gap-12"><div class="text-center"><div class="bg-gray-100 px-3 py-1 rounded-full">Marc L.</div><div class="text-xs text-gray-500 mt-1">2 filleuls</div></div><div class="text-center"><div class="bg-gray-100 px-3 py-1 rounded-full">Sophie B.</div><div class="text-xs text-gray-500 mt-1">3 filleuls</div></div></div></div></div>
              <p class="text-center text-gray-600 mt-4">Total : 128 membres</p>
              <div class="bg-blue-50 p-3 rounded-lg mt-4 text-center"><i class="fas fa-chart-line text-blue-600"></i> <span class="text-gray-700">Votre équipe a généré 3 247€ de commissions ce mois-ci.</span></div>
            </div>
          </div>

          <!-- SECTION RECOMMANDATIONS -->
          <div v-show="activeSection === 'recommandations'" class="section-content">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Mes recommandations</h1>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 overflow-x-auto">
              <table class="w-full text-left">
                <thead class="bg-gray-50"><tr><th class="p-3 text-gray-700">Nom</th><th>Email</th><th>Statut</th><th>Action</th></tr></thead>
                <tbody>
                  <tr v-for="rec in recommendations" :key="rec.nom" class="border-b"><td class="p-3">{{ rec.nom }}</td><td>{{ rec.email }}</td><td :class="rec.statusClass">{{ rec.status }}</td><td><i class="fas fa-envelope text-gray-400 cursor-pointer"></i></td></tr>
                </tbody>
              </table>
              <div class="mt-4 p-3 bg-amber-50 rounded-lg text-sm"><i class="fas fa-info-circle"></i> 10 membres actifs / 16 total</div>
            </div>
          </div>

          <!-- SECTION MON RECRUTEUR -->
          <div v-show="activeSection === 'recruteur'" class="section-content">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Mon recruteur</h1>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 max-w-lg">
              <div class="flex items-center gap-4 p-3 border-b"><div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center text-xl font-bold">JD</div><div><p class="font-bold text-lg text-gray-800">Jean Dupont</p><p class="text-blue-600">Affilié Argent</p><p class="text-sm text-gray-500">Recruteur direct</p></div></div>
              <div class="mt-4"><p class="text-gray-600"><i class="fas fa-envelope"></i> jean.dupont@livegood.com</p><p class="text-gray-600 mt-2"><i class="fas fa-phone"></i> +33 6 98 76 54 32</p></div>
            </div>
          </div>

          <!-- SECTION CLASSEMENT -->
          <div v-show="activeSection === 'classement'" class="section-content">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">🏆 Classement des meilleurs recruteurs</h1>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
              <div class="space-y-1">
                <div v-for="(leader, idx) in leaders" :key="idx" :class="['flex justify-between p-2', idx === 0 ? 'bg-yellow-50 rounded-lg' : 'hover:bg-gray-50 rounded']">
                  <span><i v-if="idx === 0" class="fas fa-crown text-yellow-500"></i> {{ idx+1 }}. {{ leader.nom }}</span>
                  <span class="font-bold">{{ leader.membres }} membres</span>
                </div>
              </div>
            </div>
          </div>

          <!-- SECTION MES SITES WEB -->
          <div v-show="activeSection === 'sitesweb'" class="section-content">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Mes sites web</h1>
            <div class="grid md:grid-cols-3 gap-5">
              <div v-for="site in sitesWeb" :key="site.type" :class="site.cardClass + ' rounded-xl p-5 shadow-sm'">
                <div class="flex items-center justify-between mb-3"><i :class="[site.icon, 'text-2xl']"></i><span :class="site.badgeClass + ' px-2 py-0.5 rounded-full text-xs'">{{ site.type }}</span></div>
                <p class="text-xs font-mono break-all">{{ site.url }}</p>
                <button @click="copyLink(site.url)" class="mt-4 w-full bg-gray-100 hover:bg-gray-200 py-2 rounded-lg text-sm transition"><i class="far fa-copy"></i> Copier</button>
              </div>
            </div>
          </div>

          <!-- SECTION WEBINAIRES -->
          <div v-show="activeSection === 'webinaires'" class="section-content">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Horaire des webinaires</h1>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
              <div class="space-y-3">
                <div v-for="web in webinaires" :key="web.jour" class="flex justify-between items-center p-3 border-b">
                  <span><i :class="[web.icon, 'text-' + web.iconColor + '-500']"></i> {{ web.jour }} {{ web.horaire }} ({{ web.langue }})</span>
                  <span :class="web.titleClass">{{ web.titre }}</span>
                </div>
              </div>
              <div class="mt-5 p-3 bg-blue-50 rounded-lg text-sm"><i class="fas fa-language"></i> Sous-titres : Français, Anglais, Espagnol, Allemand.</div>
            </div>
          </div>

          <!-- SECTION STATISTIQUES -->
          <div v-show="activeSection === 'statistiques'" class="section-content">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Statistiques avancées</h1>
            <div class="grid md:grid-cols-3 gap-5">
              <div v-for="stat in statsSites" :key="stat.nom" class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <i :class="[stat.icon, 'text-2xl mb-2']"></i>
                <p class="text-gray-500 text-sm">{{ stat.nom }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ stat.valeur }}</p>
                <p class="text-sm" :class="stat.trendClass">{{ stat.trend }}</p>
              </div>
            </div>
          </div>

          <!-- SECTION MES GAINS -->
          <div v-show="activeSection === 'gains'" class="section-content">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Mes gains</h1>
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
              <div class="grid md:grid-cols-3 gap-5 text-center p-3 border-b">
                <div><p class="text-gray-500 text-sm">Commission directe</p><p class="text-2xl font-bold text-blue-600">625,50€</p></div>
                <div><p class="text-gray-500 text-sm">Matrice (2x15)</p><p class="text-2xl font-bold text-blue-600">421,50€</p></div>
                <div><p class="text-gray-500 text-sm">Bonus équipe</p><p class="text-2xl font-bold text-blue-600">200,00€</p></div>
              </div>
              <div class="mt-4 p-3 bg-amber-50 rounded-lg text-sm"><i class="fas fa-clock"></i> Paiement hebdomadaire - Décalage +7j</div>
            </div>
          </div>

          <!-- SECTION CONTACT -->
          <div v-show="activeSection === 'contact'" class="section-content">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Contactez-nous</h1>
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 max-w-2xl">
              <p class="text-gray-600 mb-3"><i class="fas fa-envelope text-blue-500"></i> support@livegood.com</p>
              <p class="text-gray-600 mb-4"><i class="fas fa-headset"></i> Disponible 24/7</p>
              <textarea placeholder="Votre message..." class="w-full p-3 border border-gray-300 rounded-lg" rows="3"></textarea>
              <button class="mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg">Envoyer</button>
            </div>
          </div>

          <!-- SECTION LOGOUT -->
          <div v-show="activeSection === 'logout'" class="section-content">
            <div class="text-center py-16">
              <i class="fas fa-sign-out-alt text-5xl text-gray-400 mb-4"></i>
              <h2 class="text-xl font-bold text-gray-800">Vous êtes déconnecté</h2>
              <button @click="showSection('dashboard')" class="mt-5 bg-blue-600 text-white px-6 py-2 rounded-lg">Reconnecter</button>
            </div>
          </div>
          
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from 'vue'
import Chart from 'chart.js/auto'

// État
const darkMode = ref(false)
const isSidebarOpen = ref(false)
const isSidebarDesktopOpen = ref(true)
const activeSection = ref('dashboard')
let earningsChart = null

// Menu
const menuItems = [
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
  { id: 'gains', label: 'Mes gains', icon: 'fas fa-coins' },
  { id: 'contact', label: 'Contact', icon: 'fas fa-headset' },
  { id: 'logout', label: 'Déconnexion', icon: 'fas fa-sign-out-alt' }
]

// Données
const recentUsers = ref([
  { name: 'Ngab sauveur', time: '06:21:32' },
  { name: 'Lebohang Sha', time: '06:21:31' },
  { name: 'Noël Cueva', time: '06:21:23' },
  { name: 'BERNARD EMAN', time: '06:21:23' }
])

const products = [
  { name: 'Multi-Vitamine Complète', desc: '24 vitamines et minéraux', price: '9,95€', oldPrice: '17,95€', image: 'https://placehold.co/400x200/3b82f6/white?text=Multivitamine' },
  { name: 'Complexe Ultra Magnésium', desc: 'Soutien musculaire', price: '9,95€', oldPrice: '17,95€', image: 'https://placehold.co/400x200/10b981/white?text=Magn%C3%A9sium' },
  { name: 'Protéine Végétale', desc: 'Reconstruction musculaire', price: '14,95€', oldPrice: '24,95€', image: 'https://placehold.co/400x200/8b5cf6/white?text=Prot%C3%A9ine' }
]

const orders = [
  { date: '15/02/2025', product: 'Multi-Vitamine', amount: '9,95€', status: 'Livrée', statusClass: 'text-green-600' },
  { date: '01/02/2025', product: 'Magnésium Ultra', amount: '9,95€', status: 'Livrée', statusClass: 'text-green-600' },
  { date: '20/01/2025', product: 'Protéine Végétale', amount: '14,95€', status: 'En cours', statusClass: 'text-amber-600' }
]

const recommendations = [
  { nom: 'Sophie Bernard', email: 'sophie@email.com', status: 'Actif ✓', statusClass: 'text-green-600' },
  { nom: 'Marc Laurent', email: 'marc@email.com', status: 'Actif ✓', statusClass: 'text-green-600' },
  { nom: 'Julie Martin', email: 'julie@email.com', status: 'Inactif', statusClass: 'text-red-600' }
]

const leaders = [
  { nom: 'Jeffrey Aman', membres: 475 },
  { nom: 'Michael Prikazsky', membres: 238 },
  { nom: 'Erik Johnson', membres: 222 },
  { nom: 'Edward Keyte', membres: 201 },
  { nom: 'Joshua Igini', membres: 153 },
  { nom: 'Angela Holmes', membres: 145 }
]

const sitesWeb = [
  { type: 'Corporatif', url: 'https://LiveGood.com/yohann76', icon: 'fas fa-building', cardClass: 'bg-white border border-gray-200', badgeClass: 'bg-blue-100 text-blue-700' },
  { type: 'Détail', url: 'https://ShopLiveGood.com/yohann76', icon: 'fas fa-tag', cardClass: 'bg-white border border-gray-200', badgeClass: 'bg-green-100 text-green-700' },
  { type: 'Inscription', url: 'https://LiveGoodTour.com/yohann76', icon: 'fas fa-user-plus', cardClass: 'bg-gradient-to-br from-purple-50 to-indigo-50 border border-purple-200', badgeClass: 'bg-purple-200 text-purple-800' }
]

const webinaires = [
  { jour: 'Jeudi', horaire: '20h', langue: 'FR', titre: 'Co-fondateurs', icon: 'fas fa-chalkboard-user', iconColor: 'blue', titleClass: 'text-blue-600 font-semibold' },
  { jour: 'Dimanche', horaire: '18h30', langue: 'EN', titre: 'Formation produits', icon: 'fas fa-chalkboard-user', iconColor: 'green', titleClass: 'text-gray-600' },
  { jour: 'Vendredi', horaire: '21h', langue: 'EN', titre: 'Grands leaders', icon: 'fas fa-chalkboard-user', iconColor: 'purple', titleClass: 'text-gray-600' }
]

const statsSites = [
  { nom: 'Site corporatif', valeur: '1,247 visites', trend: '+12%', trendClass: 'text-green-600', icon: 'fas fa-globe text-blue-500' },
  { nom: 'Vente au détail', valeur: '856 visites', trend: '8 commandes', trendClass: 'text-amber-600', icon: 'fas fa-tag text-green-500' },
  { nom: 'Page inscription', valeur: '2,103 visites', trend: '45 pré-inscrits', trendClass: 'text-green-600', icon: 'fas fa-user-plus text-purple-500' }
]

// Fonctions
const showSection = (sectionId) => {
  activeSection.value = sectionId
  if (window.innerWidth < 1024) {
    isSidebarOpen.value = false
  }
}

const toggleSidebarDesktop = () => {
  isSidebarDesktopOpen.value = !isSidebarDesktopOpen.value
  localStorage.setItem('sidebarDesktopOpen', isSidebarDesktopOpen.value)
  // Attendre que l'animation du sidebar soit terminée pour redessiner le graphique
  setTimeout(() => {
    if (activeSection.value === 'dashboard') {
      initChart()
    }
  }, 300)
}

const toggleDarkMode = () => {
  darkMode.value = !darkMode.value
  localStorage.setItem('darkMode', darkMode.value)
  updateTheme()
  if (activeSection.value === 'dashboard') {
    initChart()
  }
}

const updateTheme = () => {
  if (darkMode.value) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }
}

const copyLink = (link) => {
  navigator.clipboard.writeText(link)
  alert('Lien copié !')
}

const initChart = () => {
  const canvas = document.getElementById('earningsChart')
  if (!canvas) return
  
  // Nettoyer l'ancien graphique
  if (earningsChart) {
    earningsChart.destroy()
    earningsChart = null
  }
  
  const ctx = canvas.getContext('2d')
  
  // Définir les couleurs selon le thème
  const gridColor = darkMode.value ? 'rgba(255, 255, 255, 0.1)' : '#e5e7eb'
  const textColor = darkMode.value ? '#9ca3af' : '#6b7280'
  
  earningsChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
      datasets: [{
        label: 'Gains (€)',
        data: [850, 1247, 1390, 1247],
        borderColor: '#3b82f6',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        borderWidth: 2,
        tension: 0.3,
        fill: true,
        pointBackgroundColor: '#3b82f6',
        pointBorderColor: '#ffffff',
        pointBorderWidth: 2,
        pointRadius: 4,
        pointHoverRadius: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          backgroundColor: darkMode.value ? '#1f2937' : '#ffffff',
          titleColor: darkMode.value ? '#ffffff' : '#1f2937',
          bodyColor: darkMode.value ? '#9ca3af' : '#6b7280',
          borderColor: darkMode.value ? '#374151' : '#e5e7eb',
          borderWidth: 1,
          padding: 10,
          displayColors: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            color: gridColor,
            drawBorder: false
          },
          ticks: {
            color: textColor,
            stepSize: 500,
            callback: function(value) {
              return value + '€'
            }
          }
        },
        x: {
          grid: {
            display: false
          },
          ticks: {
            color: textColor
          }
        }
      },
      layout: {
        padding: {
          top: 20,
          bottom: 10,
          left: 10,
          right: 10
        }
      }
    }
  })
}

onMounted(() => {
  darkMode.value = localStorage.getItem('darkMode') === 'true'
  const savedSidebarState = localStorage.getItem('sidebarDesktopOpen')
  if (savedSidebarState !== null) {
    isSidebarDesktopOpen.value = savedSidebarState === 'true'
  }
  updateTheme()
  
  // Attendre que le DOM soit complètement chargé
  nextTick(() => {
    setTimeout(() => {
      if (activeSection.value === 'dashboard') {
        initChart()
      }
    }, 200)
  })
})

// Observer le redimensionnement de la fenêtre
window.addEventListener('resize', () => {
  if (activeSection.value === 'dashboard') {
    setTimeout(initChart, 100)
  }
})
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

* { font-family: 'Inter', sans-serif; }

.sidebar-item {
  transition: all 0.2s ease;
  border-left: 3px solid transparent;
}
.sidebar-item:hover { background-color: #f3f4f6; border-left-color: #3b82f6; }
.sidebar-item.active {
  background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
  border-left-color: #3b82f6;
  color: #1d4ed8;
  font-weight: 600;
}

.scrollbar-custom::-webkit-scrollbar { width: 5px; }
.scrollbar-custom::-webkit-scrollbar-track { background: transparent; }
.scrollbar-custom::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

.section-content { animation: fadeIn 0.3s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

/* MODE SOMBRE */
.dark .bg-white { background-color: #1f2937 !important; }
.dark .bg-gray-50 { background-color: #111827 !important; }
.dark .bg-gray-100 { background-color: #374151 !important; }
.dark .text-gray-800 { color: #f3f4f6 !important; }
.dark .text-gray-900 { color: #ffffff !important; }
.dark .text-gray-500, .dark .text-gray-600, .dark .text-gray-700 { color: #9ca3af !important; }
.dark .border-gray-100, .dark .border-gray-200 { border-color: #374151 !important; }
.dark .bg-gradient-to-br.from-purple-50.to-indigo-50 { background: linear-gradient(135deg, #1e3a5f 0%, #1f2937 100%) !important; }
.dark .bg-blue-50 { background-color: #1e3a5f !important; }
.dark .bg-amber-50 { background-color: #451a03 !important; }
.dark .bg-green-100 { background-color: #064e3b !important; }
.dark .bg-purple-100 { background-color: #4c1d95 !important; }
.dark .bg-red-50 { background-color: #7f1d1d !important; }
.dark .bg-indigo-100 { background-color: #3730a3 !important; }
.dark .bg-yellow-50 { background-color: #713f12 !important; }
.dark .sidebar-item.active { background: linear-gradient(135deg, #1e3a5f 0%, #1f2937 100%) !important; color: #60a5fa !important; }
.dark .sidebar-item:hover { background-color: #374151 !important; }
.dark .hover\:bg-gray-50:hover { background-color: #374151 !important; }
.dark .hover\:bg-gray-100:hover { background-color: #374151 !important; }
.dark .hover\:bg-gray-200:hover { background-color: #4b5563 !important; }
</style>