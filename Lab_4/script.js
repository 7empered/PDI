const STORAGE_KEY = 'students_records';

function loadRecords() {
  return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
}
function saveRecords(arr) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(arr));
}

function addRecord(rec) {
  const arr = loadRecords();
  arr.push(rec);
  saveRecords(arr);
}

function updateRecord(rec) {
  const arr = loadRecords().map(r => r.id === rec.id ? rec : r);
  saveRecords(arr);
}

function deleteRecord(id) {
  const arr = loadRecords().filter(r => r.id !== id);
  saveRecords(arr);
  renderRecords();
}

const form = document.getElementById('studentForm');
const tblBody = document.querySelector('#recordsTable tbody');
const submitBtn = document.getElementById('submitBtn');
let editingId = null;

form.addEventListener('submit', e => {
  e.preventDefault();
  const rec = {
    id: editingId || Date.now(),
    firstname: form.firstname.value.trim(),
    lastname: form.lastname.value.trim(),
    middlename: form.middlename.value.trim(),
    gender: form.gender.value,
    group: form.group.value
  };
  if (editingId) {
    updateRecord(rec);
    submitBtn.textContent = 'Додати';
    editingId = null;
  } else {
    addRecord(rec);
  }
  form.reset();
  renderRecords();
});

function editRow(id) {
  const rec = loadRecords().find(r => r.id === id);
  form.firstname.value = rec.firstname;
  form.lastname.value = rec.lastname;
  form.middlename.value = rec.middlename;
  form.gender.value = rec.gender;
  form.group.value = rec.group;
  editingId = id;
  submitBtn.textContent = 'Оновити';
}

function renderRecords() {
  tblBody.innerHTML = '';
  loadRecords().forEach(r => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${r.firstname}</td>
      <td>${r.lastname}</td>
      <td>${r.middlename}</td>
      <td>${r.gender === 'male' ? 'Чоловіча' : 'Жіноча'}</td>
      <td>${r.group}</td>
      <td>
        <button type="button" onclick="editRow(${r.id})">✏️</button>
        <button type="button" onclick="deleteRecord(${r.id})">🗑️</button>
      </td>`;
    tblBody.appendChild(tr);
  });
}

function exportJSON() {
  const data = JSON.stringify(loadRecords(), null, 2);
  download('students.json', data, 'application/json');
}

function exportXML() {
  const recs = loadRecords();
  let xml = '<?xml version="1.0" encoding="UTF-8"?><students>';
  recs.forEach(r => {
    xml += `<student id="${r.id}">
      <firstname>${r.firstname}</firstname>
      <lastname>${r.lastname}</lastname>
      <middlename>${r.middlename}</middlename>
      <gender>${r.gender}</gender>
      <group>${r.group}</group>
    </student>`;
  });
  xml += '</students>';
  download('students.xml', xml, 'application/xml');
}

function download(filename, text, mime) {
  const blob = new Blob([text], { type: mime });
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = filename;
  link.click();
  URL.revokeObjectURL(link.href);
}

document.getElementById('importFile').addEventListener('change', e => {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = () => {
    try {
      let imported = [];
      if (file.name.endsWith('.json')) {
        imported = JSON.parse(reader.result);
      } else if (file.name.endsWith('.xml')) {
        const parser = new DOMParser();
        const doc = parser.parseFromString(reader.result, 'application/xml');
        imported = Array.from(doc.querySelectorAll('student')).map(node => ({
          id:   Number(node.getAttribute('id')),
          firstname: node.querySelector('firstname')?.textContent || '',
          lastname:  node.querySelector('lastname')?.textContent || '',
          middlename:node.querySelector('middlename')?.textContent || '',
          gender:    node.querySelector('gender')?.textContent || '',
          group:     node.querySelector('group')?.textContent || ''
        }));
      }
      saveRecords(imported);
      renderRecords();
    } catch(err) {
      alert('Помилка імпорту: ' + err.message);
    }
  };
  reader.readAsText(file);
});

renderRecords();