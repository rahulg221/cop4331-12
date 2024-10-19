const urlBase = 'http://cop4331-12.contacts-group12.xyz/LAMPAPI';
const extension = 'php';

let userId = 0;
let firstName = "";
let lastName = "";

/* ================================================= DO LOGIN ================================================= */

function doLogin() {
        userId = 0;
        firstName = "";
        lastName = "";

        let username = document.getElementById("loginUsername").value;
        let password = document.getElementById("loginPassword").value;

        document.getElementById("loginResult").innerHTML = "";

        let tmp = {username:username, password:password};
        let jsonPayload = JSON.stringify(tmp);

        let url = urlBase + '/Login.' + extension;

        let xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
        try {
                xhr.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                                let jsonObject = JSON.parse(xhr.responseText);
                                userId = jsonObject.id;
                                if( userId < 1 ){
                                        document.getElementById("loginResult").innerHTML = "Username/Password combination incorrect.";
                                        return;
                                }
                                firstName = jsonObject.firstName;
                                lastName = jsonObject.lastName;

                                saveCookie();

                                window.location.href = "contacts.html";
                        }
                };
                xhr.send(jsonPayload);
        }
        catch(err) {
                console.error('There was a problem with the login operation:',err.message);
        }
}

/* ================================================= DO REGISTRATION ================================================= */

function doRegistration() {
        userId = 0;
        firstName = "";
        lastName = "";

        firstName = document.getElementById("registerFirstName").value;
        lastName = document.getElementById("registerLastName").value;
        let email = document.getElementById("registerEmail").value;
        let username = document.getElementById("registerUsername").value;
        let password = document.getElementById("registerPassword").value;

        let tmp = {firstName:firstName, lastName:lastName, email:email, username:username, password:password,};
        let jsonPayload = JSON.stringify(tmp);

        let url = urlBase + '/Register.' + extension;

        let xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-type","application/json; charset=UTF-8");
        try {
                xhr.onreadystatechange = function() {
                        if(this.readyState == 4 && this.status == 200) {
                                let jsonResponse = JSON.parse(xhr.responseText);
                                userId = jsonResponse.id;
                                if(userId < 1) {
                                        return;
                                }

                                saveCookie();

                                window.location.href = "contacts.html";
                        }
                };
                xhr.send(jsonPayload);
        }
        catch(err) {
                console.error('There was a problem with the register operation:',err.message);
        }
}

/* ================================================= PROCESS COOKIES ================================================= */

function saveCookie() {
        let minutes = 20;
        let date = new Date();
        date.setTime(date.getTime()+(minutes*60*1000));
        document.cookie = "firstName=" + firstName + "; expires=" + date.toGMTString() + "; path=/";
        document.cookie = "lastName=" + lastName + "; expires=" + date.toGMTString() + "; path=/";
        document.cookie = "userId=" + userId + "; expires=" + date.toGMTString() + "; path=/";
}

function readCookie() {
        userId = -1;
        let data = document.cookie;
        let splits = data.split(";");
        for(var i = 0; i < splits.length; i++)
        {
                let thisOne = splits[i].trim();
                let tokens = thisOne.split("=");
                if(tokens[0] === "firstName")
                {
                        firstName = tokens[1];
                }
                else if(tokens[0] === "lastName")
                {
                        lastName = tokens[1];
                }
                else if(tokens[0] === "userId")
                {
                        userId = parseInt(tokens[1].trim());
                }
        }

        if(userId < 0)
        {
                window.location.href = "index.html";
        }
}

/* ================================================= DO LOGOUT ================================================= */

function doLogout() {
        userId = 0;
        firstName = "";
        lastName = "";
        document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
        window.location.href = "index.html";
}

/* ================================================= ANIMATE LOGIN/REGISTRATION ================================================= */

const wrapper = document.querySelector('.wrapper');
const loginLink = document.querySelector('.loginLink');
const registerLink = document.querySelector('.registerLink');

if(registerLink){
        registerLink.addEventListener('click', ()=> {
                wrapper.classList.add('active');
        });
}

if(loginLink) {
        loginLink.addEventListener('click', ()=> {
                wrapper.classList.remove('active');
        });
}

/* ================================================= DISPLAY CONTACTS ================================================= */

function fetchContacts() {
        const url = urlBase + '/FetchList.' + extension;
        const tmp = {userId:userId};
        const jsonPayload = JSON.stringify(tmp);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
        try
        {
                xhr.onreadystatechange = function()
                {
                        if (this.readyState == 4 && this.status == 200)
                        {
                                const data = JSON.parse(this.responseText);
                                console.log(data);
                                displayContacts(data.contacts);
                        }
                        else
                        {
                                console.error('Network response was not ok');
                        }
                };
                xhr.send(jsonPayload);
        }
        catch(err)
        {
                console.error('There was a problem with the fetch operation:',err.message);
        }
}

function displayContacts(contacts) {
    const contactList = document.getElementById('list');
    contactList.innerHTML = '';

    contacts.forEach(contact => {
        let li = document.createElement('li');
        li.innerHTML = `${contact.firstName} ${contact.lastName} (${contact.email})`;

        const span = document.createElement('span');
        span.className = 'icon';
        span.innerHTML = "<i class='iconoir-edit-pencil'></i>";

        span.onclick = function() {
            openEditPopup(contact.firstName, contact.lastName, contact.email);
        };

        li.appendChild(span);
        contactList.appendChild(li);
    });
}

/* ================================================= ADD CONTACT ================================================= */

function openNewPopup() {
    document.getElementById('newPopup').style.display = 'flex';
}

function closeNewPopup() {
    document.getElementById('newPopup').style.display = 'none';
}

const addButton = document.getElementById("addButton");
if(addButton){
        document.getElementById("addButton").addEventListener("click", openNewPopup);
}

function newContact() {
        let newFirst = document.getElementById("newContactFirst").value;
        let newLast = document.getElementById("newContactLast").value;
        let newEmail = document.getElementById("newContactEmail").value;

        let tmp = {firstName:newFirst, lastName:newLast, email:newEmail, userId:userId};
        let jsonPayload = JSON.stringify(tmp);

        let url = urlBase + '/Contacts.' + extension;

        let xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
        try
        {
                xhr.onreadystatechange = function()
                {
                        if (this.readyState == 4 && this.status == 200)
                        {
                                fetchContacts();

                                document.getElementById("newContactFirst").value = "";
                                document.getElementById("newContactLast").value = "";
                                document.getElementById("newContactEmail").value = "";

                                closeNewPopup();
                        }
                };
                xhr.send(jsonPayload);
        }
        catch(err)
        {
                console.error('There was a problem with the contact adding operation:',err.message);
        }
}

/* ================================================= EDIT/DELETE CONTACT ================================================= */

let uneditedContact = {};

const saveButton = document.getElementById("saveContact");
const deleteButton = document.getElementById("deleteContact");

function openEditPopup(firstName, lastName, email) {
    uneditedContact = {firstName, lastName, email};

    document.getElementById('editFirstName').value = firstName;
    document.getElementById('editLastName').value = lastName;
    document.getElementById('editEmail').value = email;
    document.getElementById('editPopup').style.display = 'flex';
}

function closeEditPopup() {
    document.getElementById('editPopup').style.display = 'none';
}

if(saveButton) {
        saveButton.addEventListener("click", () => {
                const editedFirst = document.getElementById('editFirstName').value;
                const editedLast = document.getElementById('editLastName').value;
                const editedEmail = document.getElementById('editEmail').value;

                editContact(editedFirst, editedLast, editedEmail);
                closeEditPopup();
        });
}

if(deleteButton) {
        deleteButton.addEventListener("click", () => {
                deleteContact(uneditedContact.firstName, uneditedContact.lastName, uneditedContact.email);
                closeEditPopup();
        });
}

function editContact(editedFirst, editedLast, editedEmail) {
        let tmp = {newFirstName:editedFirst, newLastName:editedLast, newEmail:editedEmail, firstName:uneditedContact.firstName, lastName:uneditedContact.lastName, email:uneditedContact.email, userId:userId};
        let jsonPayload = JSON.stringify(tmp);

        let url = urlBase + '/EditContact.' + extension;

        let xhr = new XMLHttpRequest();
        xhr.open("PUT", url, true);
        xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
        try
        {
                xhr.onreadystatechange = function()
                {
                        if (this.readyState == 4 && this.status == 200)
                        {
                                fetchContacts();
                        }
                };
                xhr.send(jsonPayload);
        }
        catch(err)
        {
                console.error('There was a problem with the contact editing operation:',err.message);
        }
}

function deleteContact(contactFirst, contactLast, contactEmail) {
        let tmp = {firstName:contactFirst, lastName:contactLast, email:contactEmail, userId:userId};
        let jsonPayload = JSON.stringify(tmp);

        let url = urlBase + '/DeleteContact.' + extension;

        let xhr = new XMLHttpRequest();
        xhr.open("DELETE", url, true);
        xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
        try
        {
                xhr.onreadystatechange = function()
                {
                        if (this.readyState == 4 && this.status == 200)
                        {
                                fetchContacts();
                        }
                };
                xhr.send(jsonPayload);
        }
        catch(err)
        {
                console.error('There was a problem with the contact deleting operation:',err.message);
        }
}

/* ================================================= SEARCH CONTACT ================================================= */

const searchButton = document.getElementById("search");
if(searchButton){
        searchButton.addEventListener("click", searchContact);
}

function searchContact(){
    let srch = document.getElementById("searchContacts").value;

    let tmp = {search:srch, userId:userId};
    let jsonPayload = JSON.stringify(tmp);

    let url = urlBase + '/FindContact.' + extension;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");

    try{
        xhr.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                const data = JSON.parse(this.responseText);
                displayContacts(data.results);
            }
        };
        xhr.send(jsonPayload);
    }
    catch(err){
        console.error('There was a problem with the search operation:',err.message);
    }
}