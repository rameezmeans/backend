import Alpine from "alpinejs";
import Focus from "@alpinejs/focus";

Alpine.plugin(Focus);
Alpine.plugin(FormsAlpinePlugin);
Alpine.plugin(NotificationsAlpinePlugin);

window.Alpine = Alpine;

Alpine.start();
