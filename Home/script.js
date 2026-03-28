(function(){
  // configuration
  const HOSTEL_COUNT = 7;
  const HOSTEL_PREFIX = 'Hostel';
  const STORAGE_KEY = 'studentLogs_v1';

  // elements
  const hostelSelect = document.getElementById('hostel');
  const hostelNav = document.getElementById('hostel-nav');
  const logBody = document.getElementById('log-body');
  const form = document.getElementById('student-form');
  const logBtn = document.getElementById('log-btn');
  const searchInput = document.getElementById('search');
  const filterMovement = document.getElementById('filter-movement');
  const totalEl = document.getElementById('total');
  const countInEl = document.getElementById('count-in');
  const countOutEl = document.getElementById('count-out');
  const exportBtn = document.getElementById('export-csv');
  const clearBtn = document.getElementById('clear-all');

  // state
  let logs = [];
  let activeHostel = '';

  // init hostels
  function initHostels(){
    hostelSelect.innerHTML = '<option value="">Select hostel</option>';
    hostelNav.innerHTML = '';
    const allBtn = document.createElement('button');
    allBtn.textContent = 'All';
    allBtn.classList.add('active');
    allBtn.onclick = () => { setActiveHostel(''); };
    hostelNav.appendChild(allBtn);

    for(let i=1;i<=HOSTEL_COUNT;i++){
      const name = HOSTEL_PREFIX + ' ' + i;
      const opt = document.createElement('option'); 
      opt.textContent = name; 
      opt.value = name; 
      hostelSelect.appendChild(opt);

      const btn = document.createElement('button'); 
      btn.textContent = name;
      btn.onclick = () => { setActiveHostel(name); };
      hostelNav.appendChild(btn);
    }
  }

  function setActiveHostel(name){
    activeHostel = name;
    Array.from(hostelNav.children).forEach(b => 
      b.classList.toggle('active', b.textContent === (name || 'All'))
    );
    renderLogs();
  }

  // time helper
  function toLocalISO(dt){
    const d = new Date(dt);
    const pad = s => String(s).padStart(2,'0');
    const Y = d.getFullYear(), M = pad(d.getMonth()+1), D = pad(d.getDate());
    const h = pad(d.getHours()), m = pad(d.getMinutes());
    return `${Y}-${M}-${D} ${h}:${m}`;
  }

  function nowForInput(){
    const d = new Date();
    d.setSeconds(0,0);
    const tzOffset = d.getTimezoneOffset();
    const local = new Date(d.getTime() - tzOffset*60000);
    return local.toISOString().slice(0,16);
  }

  // storage
  function load(){
    try{ logs = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]'); }
    catch(e){ logs = []; }
  }
  function save(){ localStorage.setItem(STORAGE_KEY, JSON.stringify(logs)); }

  // add log
  function addLog(entry){ logs.unshift(entry); save(); renderLogs(); }

  // delete
  function deleteLog(index){ 
    if(confirm('Delete this record?')){
      logs.splice(index,1); 
      save(); 
      renderLogs(); 
    } 
  }

  // export csv
  function exportCSV(){
    if(!logs.length){ alert('No records to export'); return; }
    const header = ['time','name','roll','year','branch','hostel','room','type'];
    const rows = logs.map(r => 
      header.map(h => '"' + (r[h] || '').replace(/"/g,'""') + '"').join(',')
    );
    const csv = [header.join(','), ...rows].join('\n');
    const blob = new Blob([csv], {type:'text/csv'});
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a'); 
    a.href = url; 
    a.download = 'student_logs.csv'; 
    a.click(); 
    URL.revokeObjectURL(url);
  }

  // render
  function renderLogs(){
    const q = searchInput.value.trim().toLowerCase();
    const typeFilter = filterMovement.value;
    logBody.innerHTML = '';
    let inCount = 0, outCount = 0;
    logs.forEach((r, i) => {
      if(activeHostel && r.hostel !== activeHostel) return;
      if(typeFilter && r.type !== typeFilter) return;
      if(q){ 
        const hay = [r.name,r.roll,r.branch,r.hostel,r.room,r.time].join(' ').toLowerCase(); 
        if(!hay.includes(q)) return; 
      }

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${r.time}</td>
        <td>${r.name}</td>
        <td>${r.roll}</td>
        <td>${r.year}</td>
        <td>${r.branch}</td>
        <td>${r.hostel}</td>
        <td>${r.room}</td>
        <td><span class="badge ${r.type==='IN'?'in':'out'}">${r.type}</span></td>
        <td><button class="btn btn-ghost" data-index="${i}">Delete</button></td>`;
      logBody.appendChild(tr);

      if(r.type === 'IN') inCount++; 
      else outCount++;
    });
    totalEl.textContent = logs.length;
    countInEl.textContent = inCount;
    countOutEl.textContent = outCount;
  }

  // wire events
  document.getElementById('time').value = nowForInput();
  document.getElementById('student-year').value = '';

  logBtn.addEventListener('click', ()=>{
    const name = document.getElementById('student-name').value.trim();
    const roll = document.getElementById('roll').value.trim();
    const year = document.getElementById('student-year').value;
    const branch = document.getElementById('branch').value.trim();
    const hostel = document.getElementById('hostel').value;
    const room = document.getElementById('room').value.trim();
    const type = document.getElementById('movement').value;
    const time = document.getElementById('time').value;

    if(!name||!roll||!year||!branch||!hostel||!room||!time){ 
      alert('Please fill required fields'); 
      return; 
    }

    const entry = {time: toLocalISO(time), name, roll, year, branch, hostel, room, type};
    addLog(entry);
    form.reset(); 
    document.getElementById('time').value = nowForInput();
  });

  searchInput.addEventListener('input', renderLogs);
  filterMovement.addEventListener('change', renderLogs);

  exportBtn.addEventListener('click', exportCSV);
  clearBtn.addEventListener('click', ()=>{
    if(confirm('Clear all records?')){
      logs=[]; 
      save(); 
      renderLogs(); 
    }
  });

  logBody.addEventListener('click', e=>{
    if(e.target.matches('button[data-index]')){
      const idx = Number(e.target.getAttribute('data-index'));
      deleteLog(idx);
    }
  });

  // time display
  function updateClock(){ 
    const el = document.getElementById('local-time'); 
    const d = new Date(); 
    el.textContent = String(d.getHours()).padStart(2,'0')+':'+String(d.getMinutes()).padStart(2,'0'); 
  }
  updateClock(); 
  setInterval(updateClock,30000);

  document.getElementById('student-year').value = '';
  document.getElementById('student-year').dispatchEvent(new Event('change'));

  // year in footer
  document.getElementById('year').textContent = new Date().getFullYear();

  // init
  initHostels(); 
  load(); 
  renderLogs();

})();
