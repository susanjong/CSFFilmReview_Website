fetch('/PROJEK AKHIR_PEMWEB/PROJEK PEMWEB AKHIR/footer/footer.html')
      .then(response => response.text())
      .then(data => {
        document.body.insertAdjacentHTML('beforeend', data);
      });