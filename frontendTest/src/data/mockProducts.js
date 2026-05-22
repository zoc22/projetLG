const baseProducts = [
  { id: 1, category: 'Vitamines', name: 'Multi-Vitamine Complète Hommes', desc: '24 vitamines et minéraux essentiels pour booster votre énergie et immunité.', price: '9.95$', oldPrice: '17.95$', image: 'https://images.unsplash.com/photo-1584017945366-b97b099b1fe7?auto=format&fit=crop&w=400&q=80', badge: 'Best Seller' },
  { id: 2, category: 'Vitamines', name: 'Multi-Vitamine Complète Femmes', desc: 'Formule optimisée pour le bien-être féminin avec fer et acide folique.', price: '9.95$', oldPrice: '17.95$', image: 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?auto=format&fit=crop&w=400&q=80', badge: 'Best Seller' },
  { id: 3, category: 'Nutrition', name: 'Complexe Ultra Magnésium', desc: 'Soutien musculaire et nerveux de haute biodisponibilité.', price: '8.95$', oldPrice: '14.95$', image: 'https://images.unsplash.com/photo-1616671285410-0938363a0339?auto=format&fit=crop&w=400&q=80', badge: 'Top Qualité' },
  { id: 4, category: 'Nutrition', name: 'Protéine Végétale Bio', desc: 'Reconstruction musculaire complète à base de pois et chanvre.', price: '22.00$', oldPrice: '34.00$', image: 'https://images.unsplash.com/photo-1593095948071-474c5cc2989d?auto=format&fit=crop&w=400&q=80', badge: 'Vegan' },
  { id: 5, category: 'Superfoods', name: 'Super Greens Biologiques', desc: 'Mélange détox de spiruline, chlorelle et herbe de blé.', price: '18.00$', oldPrice: '29.95$', image: 'https://images.unsplash.com/photo-1610970881699-44a5587cabec?auto=format&fit=crop&w=400&q=80', badge: 'Bio' },
  { id: 6, category: 'Superfoods', name: 'Super Reds Énergie', desc: 'Antioxydants puissants pour la santé cardiaque et circulatoire.', price: '18.00$', oldPrice: '29.95$', image: 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=400&q=80', badge: 'Energie' },
  { id: 7, category: 'Bien-être', name: 'Huile CBD Full Spectrum', desc: '750mg de CBD pur pour apaiser le stress et améliorer le sommeil.', price: '18.00$', oldPrice: '59.00$', image: 'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=400&q=80', badge: '-70% Promo' },
  { id: 8, category: 'Bien-être', name: 'Facteur 4 Anti-Inflammatoire', desc: 'Mélange puissant d\'oméga-3, curcuma et coenzyme Q10.', price: '18.50$', oldPrice: '29.95$', image: 'https://images.unsplash.com/photo-1471864190281-a93a3070b6de?auto=format&fit=crop&w=400&q=80', badge: 'Nouveauté' },
  { id: 9, category: 'Beauté', name: 'Sérum Anti-Âge Ageless', desc: 'Effet lifting immédiat et réduction visible des rides.', price: '14.95$', oldPrice: '24.95$', image: 'https://images.unsplash.com/photo-1570172619380-41da07df0822?auto=format&fit=crop&w=400&q=80', badge: 'Beauté' },
  { id: 10, category: 'Beauté', name: 'Crème de Nuit Régénératrice', desc: 'Hydratation intense pendant votre sommeil pour une peau éclatante.', price: '12.00$', oldPrice: '22.00$', image: 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?auto=format&fit=crop&w=400&q=80', badge: 'Beauté' },
  { id: 11, category: 'Accessoires', name: 'Shaker LiveGood Premium', desc: 'Sans BPA, mélange parfait pour vos protéines et supergreens.', price: '5.00$', oldPrice: '12.00$', image: 'https://images.unsplash.com/photo-1620188467120-5042ed1eb5da?auto=format&fit=crop&w=400&q=80', badge: 'Accessoire' },
  { id: 12, category: 'Vitamines', name: 'Vitamine D3-K2 Haute Puissance', desc: 'Fixation du calcium et soutien immunitaire optimal.', price: '8.50$', oldPrice: '14.00$', image: 'https://images.unsplash.com/photo-1550573105-df255909241b?auto=format&fit=crop&w=400&q=80', badge: 'Indispensable' }
];

export const productsData = [];

for (let i = 0; i < 6; i++) {
  baseProducts.forEach(p => {
    productsData.push({
      ...p,
      id: p.id + (i * 100),
      name: i === 0 ? p.name : `${p.name} (Lot ${i + 1})`
    });
  });
}

export const marketplaceBanners = [
  { id: 1, title: 'Promotion Exceptionnelle !', text: 'Jusqu\'à 75% d\'économie sur vos produits préférés.', bg: 'from-blue-600 to-indigo-700' },
  { id: 2, title: 'Nouveauté Facteur 4', text: 'Combattez l\'inflammation naturellement avec notre nouvelle formule.', bg: 'from-red-500 to-pink-600' },
  { id: 3, title: 'Adhésion Annuelle', text: 'Passez à l\'annuel et économisez encore plus sur vos commandes.', bg: 'from-green-500 to-teal-600' }
];
