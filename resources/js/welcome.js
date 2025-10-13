document.addEventListener("DOMContentLoaded", function () {
  const userInput = document.getElementById("name");
  const partnerInput = document.getElementById("your_name");
  const msgDiv = document.getElementById("message");

  const template = `Szia <strong>{{user}}</strong>!\n\nEzt az e-mailt a párod, <strong>{{partner}}</strong> kérésére küldjük neked. <strong>{{partner}}</strong> most csatlakozott a Jobb Házasság Akadémia programhoz, amely egy 5 hetes bevezetés a párkapcsolatok pszichológiájába. A program ideje alatt, 5 héten át, minden kedden és pénteken küldünk egy leckét a résztvevőknek a hosszú távú párkapcsolatok pszichológiai alapjairól.\n\nHa te is szeretnél tanulni kicsit a párkapcsolatokról, akkor azt most teljesen ingyen megteheted. A program 2.900 Ft volt és a bevezető szakaszban most mindenki hozzáadhatja a párját is a programhoz.\n\nHa elfogadod a meghívást, együtt haladhattok végig a leckéken, és így sokkal nagyobb lesz a hatása annak, amit tanultok.\n\nÜdv,\nJobb Házasság Akadémia`;

  function updateMessage() {
    const partnerName = partnerInput.value.trim() || "Felhasználó";
    const userName = userInput.value.trim() || "Párod neve";

    msgDiv.innerHTML = template
      .replace(/{{partner}}/g, partnerName)
      .replace(/{{user}}/g, userName);
  }

  updateMessage();
  partnerInput.addEventListener("input", updateMessage);
  userInput.addEventListener("input", updateMessage);
});
