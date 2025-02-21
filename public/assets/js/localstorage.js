window.addEventListener('beforeunload', function () {
    localStorage.removeItem('personId');
    localStorage.removeItem('documentId');
    localStorage.removeItem('atletId');

});
