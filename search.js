document.getElementById('search').addEventListener('input', function(){
  const q = this.value;
  fetch('/api/ajax_search.php?q=' + encodeURIComponent(q))
    .then(r => r.json())
    .then(data => {
      const wrap = document.getElementById('results');
      if(!data || !data.length) { wrap.innerHTML = '<p>No results</p>'; return; }
      wrap.innerHTML = data.map(c => `
        <div style="border:1px solid #ccc;padding:8px;margin:6px;">
          <h4>${c.title}</h4>
          <p>${(c.description||'').substring(0,120)}...</p>
          <a href="/course.php?id=${c.id}">View</a>
        </div>
      `).join('');
    })
    .catch(e => console.error(e));
});
