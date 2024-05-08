<script>
	export let questionLenght;
	export let quizId;
	import LinkBtn from './LinkBtn.svelte';
	import { punteggio, user } from '/src/store.js';

	import { onMount } from 'svelte';

	const uploadScore = (idUser, idQuiz, score) => {
		console.log('caricando: ', idUser, idQuiz, score);
		fetch('http://localhost/backend-quiz-daver/backend-quizSvelte/post-score.php', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({
				idUser: idUser,
				idQuiz: idQuiz,
				score: score
			})
		})
			.then((res) => {
				if (!res.ok) {
					// Se la richiesta non ha avuto successo, lanciamo un errore con lo stato della risposta
					throw new Error(`Request failed with status ${res.status}`);
				}
				return res.json();
			})
			.catch((error) => {
				// Gestisci gli errori qui
				console.log('Error registering score:', error.message);
				currentError = error.message; // Aggiorna la variabile currentError con il messaggio di errore
			});
	};

	onMount(() => {
		uploadScore($user.id, quizId, $punteggio / questionLenght);
	});
</script>

<div class="flex flex-col items-center">
	<img src="\scoreIcon.png" width="" height="" alt="" class="w-20 h-20" />
	<h2 class="text-3xl font-bold mb-10 text-secondary">Il tuo punteggio:</h2>
	<div class="flex flxel-col gap-14 mb-5">
		<div class="text-white flex flex-col gap-y-5 bg-green py-3 px-10 rounded-lg">
			<p class="font-bold text-7xl">{$punteggio}</p>
			<p class="text-sm font-bold">RISPOSTE<br />CORRETTE</p>
		</div>
		<div class="bg-red text-white flex flex-col gap-y-5 py-3 px-10 rounded-lg">
			<p class="font-bold text-7xl">{questionLenght - $punteggio}</p>
			<p class="text-sm font-bold">RISPOSTE<br />ERRATE</p>
		</div>
	</div>

	<a href="/globalStats" class="text-secondary mb-2">Visualizza classifica globale</a>
	<LinkBtn url="/selezione" name="Torna alla Home" colorIndex="4" />
</div>
