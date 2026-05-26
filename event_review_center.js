document.addEventListener("DOMContentLoaded", () => {

  const burgerBtn = document.getElementById("burgerBtn");
  const mobileMenu = document.getElementById("mobileMenu");

  if (burgerBtn && mobileMenu) {
    burgerBtn.addEventListener("click", () => {
      mobileMenu.classList.toggle("is-open");
      burgerBtn.setAttribute(
        "aria-expanded",
        mobileMenu.classList.contains("is-open") ? "true" : "false"
      );
    });

    mobileMenu.addEventListener("click", (e) => {
      if (e.target.tagName === "A") {
        mobileMenu.classList.remove("is-open");
        burgerBtn.setAttribute("aria-expanded", "false");
      }
    });
  }

  let offset =
    typeof window.EVENT_OFFSET === "number" ? window.EVENT_OFFSET : 0;

  let loading = false;
  let finished = false;

  const grid = document.getElementById("eventGrid");
  if (!grid) {
    console.warn("eventGrid not found");
    return;
  }


  const seen = new Set();
  document.querySelectorAll(".event-card[data-id]").forEach((el) => {
    const id = String(el.dataset.id || "");
    if (id) seen.add(id);
  });


  let loadingEl = document.getElementById("loading");
  if (!loadingEl) {
    loadingEl = document.createElement("div");
    loadingEl.id = "loading";
    loadingEl.className = "loading";
    loadingEl.style.display = "none";
    loadingEl.textContent = "Loading more events...";
    grid.parentNode && grid.parentNode.appendChild(loadingEl);
  }


  const scrollContainer = document.querySelector(".main") || window;

  function isNearBottom() {
    if (scrollContainer === window) {
      return (
        window.innerHeight + window.scrollY >=
        document.body.offsetHeight - 200
      );
    }
    const el = scrollContainer;
    return el.scrollTop + el.clientHeight >= el.scrollHeight - 200;
  }

  async function loadMoreEvents() {
    if (loading || finished) return;

    loading = true;
    loadingEl.style.display = "block";
    loadingEl.textContent = "Loading more events...";

    try {
      const res = await fetch(load_events.php?offset=${offset}, {
        cache: "no-store",
      });

      const contentType = res.headers.get("content-type") || "";
      if (!contentType.includes("application/json")) {
        const text = await res.text();
        console.error("Not JSON:", text.slice(0, 200));
        throw new Error("Server did not return JSON (check load_events.php).");
      }

      const data = await res.json();

      if (!Array.isArray(data) || data.length === 0) {
        finished = true;
        loadingEl.textContent = "No more events";
        return;
      }

      let appendedCount = 0;

      data.forEach((ev) => {
        const id = String(ev.event_id || "");
        if (!id) return;

        if (seen.has(id)) return;
        seen.add(id);

        const card = document.createElement("a");
        card.className = "event-card";
        card.dataset.id = id;

        card.href = event_detail.php?event_id=${encodeURIComponent(id)};

        const desc = ev.description || "";
        const shortDesc = desc.length > 80 ? desc.slice(0, 80) + "..." : desc;

        card.innerHTML = `
          <div class="event-title">${escapeHtml(ev.name || "")}</div>
          <div class="event-date">
            ${escapeHtml(ev.start_date || "")} ~ ${escapeHtml(ev.end_date || "")}
          </div>
          <div class="event-desc">${escapeHtml(shortDesc)}</div>

          <div class="progress-wrap">
            <div class="progress-bar" style="width:${Number(ev.progress || 0)}%"></div>
          </div>

          <div class="event-meta">
            <span class="reward">Reward: ${Number(ev.reward_point || 0)} pts</span>
            <span class="pid">#${escapeHtml(id)}</span>
          </div>
        `;

        grid.appendChild(card);
        appendedCount++;
      });

      offset += data.length;

      if (appendedCount === 0 && !finished) {
        await loadMoreEvents();
      }
    } catch (err) {
      console.error("Load failed:", err);
      loadingEl.textContent = "Load failed. Try again.";
    } finally {
      loading = false;
      if (!finished) loadingEl.style.display = "none";
    }
  }

  scrollContainer.addEventListener("scroll", () => {
    if (isNearBottom()) loadMoreEvents();
  });

  if (scrollContainer === window) {
    window.addEventListener("scroll", () => {
      if (isNearBottom()) loadMoreEvents();
    });
  }

  setTimeout(() => {
    if (isNearBottom()) loadMoreEvents();
  }, 200);

  function escapeHtml(str) {
    return String(str)
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#039;");
  }
});