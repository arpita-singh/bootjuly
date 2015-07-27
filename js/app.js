// Document ready - taken from Internet
var ready = function(fn) {
    if (document.readyState != 'loading') {
        fn();
    } else {
        document.addEventListener('DOMContentLoaded', fn);
    }
};

// Multiple event listeners - taken from the Internet
var addListenerMulti = function(el, s, fn) {
    var evts = s.split(' ');
    for (var i = 0, iLen = evts.length; i < iLen; i++) {
        el.addEventListener(evts[i], fn, false);
    }
};

// Create UUIDs - taken from Internet
var guid = function() {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
        s4() + '-' + s4() + s4() + s4();
};

// Add to storage
var addToStorageAndGo = function(username) {

    var users;

    var newUser = {
        username: username,
        session: guid(),
        playCount: 0,
    };

    if (localStorage.getItem('users')) {

        // Get users from localStorage
        users = JSON.parse(localStorage.getItem('users'));

        // Add new user to array of users
        users.push(newUser);

        // Sort users alphabetically - taken from Internet
        users.sort(function(a, b) {

            var userA = a.username.toLowerCase(),
                userB = b.username.toLowerCase();

            if (userA < userB)
                return -1;
            if (userA > userB)
                return 1;

            return 0;

        });

    } else {

        users = [newUser];

    }

    // Set the local storage
    localStorage.setItem('users', JSON.stringify(users));

    // Set cookie, and go!
    createCookieAndGo(newUser);

};

// Creates cookie and continues
var createCookieAndGo = function(user) {

    // Set cookie
    document.cookie = 'lrnuser=' + user.username + ',' + user.session;

    // Go to next page
    window.location.href = 'assessment.php';

};

// Document ready...
ready(function() {

    // Because, strict mode.
    "use strict";

    // Only do the following if we have users in storage
    if (localStorage.getItem('users')) {

        // Get users from localStorage
        var users = JSON.parse(localStorage.getItem('users'));

        // Remove 'no users' option
        var element = document.getElementsByTagName('option')[0];
        element.parentNode.removeChild(element);

        // Add options to existing-user select
        var i = 0,
            l = users.length;
        var select = document.getElementById('existing-user');
        for (i; i < l; i++) {

            var option = document.createElement("option");
            option.text = users[i].username;
            option.value = users[i].session;
            select.add(option);

        }

        // Show section, enable continue button and set event
        document.getElementsByClassName('s-existing-user')[0].className = 's-existing-user';
        var continueBtn = document.getElementById('continue');
        continueBtn.disabled = false;
        continueBtn.addEventListener('click', function() {
            var selectedOption = document.querySelector('#existing-user option:checked');
            createCookieAndGo({
                username: selectedOption.text,
                session: selectedOption.value
            });
        }, false);

        // Show section, enable clear button and set event
        document.getElementsByClassName('s-reset')[0].className = 's-reset';
        var resetBtn = document.getElementById('reset');
        resetBtn.disabled = false;
        resetBtn.addEventListener('click', function() {
            localStorage.removeItem('users');
            location.reload();
        }, false);

    }

    // Set create button to a variable for speed
    var createBtn = document.getElementById('create');

    // Set validation for new-user
    var newUserField = document.getElementById('new-user');
    // newUserField.oninvalid = function(e) {
    //     this.setCustomValidity('');
    //     this.setCustomValidity("Please enter an alpha-numeric username, with no spaces or special characters.");
    // };
    // newUserField.oninput = function(e) {
    //     this.setCustomValidity("Test");
    // };

    // Add event listener to create button
    createBtn.addEventListener('click', function() {

        addToStorageAndGo(newUserField.value);

    }, false);

    // Add multiple event listeners to the new-user input
    addListenerMulti(newUserField, 'keyup keydown blur focus click', function(e) {

        var newUserLabel = document.querySelector('label[for="new-user"]');

        // Disable the button if need be
        if (this.value.length && this.validity.valid) {
            createBtn.disabled = false;
            if (newUserLabel.dataset.orinalLabel) {
                newUserLabel.innerHTML = newUserLabel.dataset.orinalLabel;
            }
        } else if (this.value.length) {
            createBtn.disabled = true;
            if (!newUserLabel.dataset.orinalLabel) {
                newUserLabel.dataset.orinalLabel = newUserLabel.innerHTML;
            }
            newUserLabel.innerHTML = 'Enter an alphanumeric username, spaces and special characters are not allowed:';
        } else {
            createBtn.disabled = true;
        }

    });

});
