document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('sidebar-toggle');
    const wrapper = document.getElementById('wrapper');
  
    if (toggleBtn && wrapper) {
      toggleBtn.addEventListener('click', () => {
        wrapper.classList.toggle('sidebar-closed');
      });
    }
  });