// Récupérez tous les éléments de navigation
  const navItems = document.querySelectorAll('.nav-item');

  // Récupérez toutes les sections
  const sections = document.querySelectorAll('div[id^="compte"]');

  // Ajoutez un gestionnaire d'événements à chaque élément de navigation
  navItems.forEach((navItem) => {
    navItem.addEventListener('click', () => {
      // Retirez la classe active de tous les éléments de navigation
      navItems.forEach((item) => item.classList.remove('active'));

      // Ajoutez la classe active à l'élément de navigation actuel
      navItem.classList.add('active');

      // Obtenez la section associée à l'élément de navigation
      const sectionId = navItem.dataset.section;
      const targetSection = document.getElementById(sectionId);

      // Faites défiler jusqu'à la section
      targetSection.scrollIntoView({ behavior: 'smooth' });
    });
  });

  // Ajoutez un gestionnaire d'événements pour suivre le défilement de la page
  window.addEventListener('scroll', () => {
    // Obtenez la position de défilement actuelle
    const scrollPosition = window.scrollY;

    // Parcourez chaque section pour trouver celle qui est actuellement visible
    sections.forEach((section) => {
      const sectionTop = section.offsetTop - 50; // Ajustez selon vos besoins

      if (scrollPosition >= sectionTop && scrollPosition < sectionTop + section.offsetHeight) {
        // Retirez la classe active de tous les éléments de navigation
        navItems.forEach((item) => item.classList.remove('active'));

        // Obtenez l'élément de navigation associé à la section actuellement visible
        const targetNavItem = document.querySelector(`.nav-item[data-section="${section.id}"]`);

        // Ajoutez la classe active à l'élément de navigation associé à la section actuellement visible
        targetNavItem.classList.add('active');
      }
    });
  });