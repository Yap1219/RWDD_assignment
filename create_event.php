<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Event</title>
  <link rel="stylesheet" href="create_event.css">
</head>
<body>

<div class="page">

  <header class="nav">
    <a class="homeLink" href="admin_home.php">HOME</a>
    <div class="nav__title">CREATE EVENT</div>
    <div class="nav__right">
      <div class="avatar" title="Admin"></div>
    </div>
  </header>

  <main class="main">
    <div class="wrap">

      <form
        id="createEventForm"
        class="glass form"
        method="post"
        action="create_event_submit.php"
        enctype="multipart/form-data"
      >

        <h2 class="sectionTitle">Event Info</h2>

        <div class="field">
          <label>Event Name</label>
          <input type="text" name="name" placeholder="Enter event name" required>
        </div>

        <div class="row">
          <div class="field">
            <label>Start Date</label>
            <input type="date" name="start_date" required>
          </div>
          <div class="field">
            <label>End Date</label>
            <input type="date" name="end_date" required>
          </div>
        </div>

        <div class="field">
          <label>Reward Point</label>
          <input type="number" name="reward_point" min="0" value="0" required>
        </div>

        <div class="field">
          <label>Description</label>
          <textarea name="description" rows="6" placeholder="Describe the event..." required></textarea>
        </div>

        <div class="field">
          <label>Photo (Optional)</label>
          <input id="photoInput" type="file" name="photo" accept="image/*">
        </div>

        <div class="actions">
          <button class="btn btnPrimary" type="submit">CREATE</button>
          <a class="btn btnGhost" href="event_review_center.php">CANCEL</a>
        </div>
      </form>
      <div class="glass preview">
        <div class="previewHead">
          <h2 class="sectionTitle">Photo Preview</h2>
        </div>

        <div class="photoBox" id="photoBox">
          <div class="photoHint">No image selected</div>
          <img id="photoPreview" alt="Preview" style="display:none;">
        </div>

        <div class="tips">
          Tip: JPG / PNG / WEBP recommended.
        </div>
      </div>

    </div>
  </main>

</div>

<script>
  document.addEventListener("DOMContentLoaded", () => {
  const input = document.getElementById("photoInput");
  const img = document.getElementById("photoPreview");
  const hint = document.querySelector(".photoHint");

  if (!input || !img) return;

  input.addEventListener("change", () => {
    const file = input.files && input.files[0];

    if (!file) {
      img.removeAttribute("src");
      img.style.display = "none";
      if (hint) hint.style.display = "block";
      return;
    }

    img.src = URL.createObjectURL(file);
    img.style.display = "block";
    if (hint) hint.style.display = "none";
  });
});
</script>

</body>
</html>
