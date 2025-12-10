const openModalBtn = document.getElementById('openSizeGuide');
    const closeModalBtn = document.getElementById('closeSizeGuide');
    const modal = document.getElementById('sizeGuideModal');

    openModalBtn.addEventListener('click', () => modal.classList.remove('hidden'));
    closeModalBtn.addEventListener('click', () => modal.classList.add('hidden'));
    modal.addEventListener('click', (e) => {
      if (e.target === modal) modal.classList.add('hidden');
    });