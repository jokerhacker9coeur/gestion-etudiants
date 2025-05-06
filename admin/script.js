document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
      document.body.classList.add('dark-mode');
      document.getElementById('themeIcon').classList.replace('fa-moon', 'fa-sun');
    }
  });
  
  function toggleTheme() {
    const body = document.body;
    const icon = document.getElementById('themeIcon');
    const isDark = body.classList.toggle('dark-mode');
    icon.classList.toggle('fa-moon');
    icon.classList.toggle('fa-sun');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
  }
  