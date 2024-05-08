<script>
	import { user } from '../store';
	import { goto } from '$app/navigation';
	import { notifications } from './notifications';
	import Toast from './Toast.svelte';
	import LinkBtn from './LinkBtn.svelte';
	let username = '';
	let password = '';
	let email = '';
	let currentError = null;

	const saveUser = (user) => {
		localStorage.setItem('user', JSON.stringify(user));
	};

	const login = () => {
		//fetch al server

		if (!username) {
			currentError = 'Username is empty';
			return;
		}
		if (!password) {
			currentError = 'Password is empty';
			return;
		}

		fetch('http://localhost/backend-quiz-daver/backend-quizSvelte/create-user.php', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({
				username: username,
				password: password,
				email: email
			})
		})
			.then((res) => {
				if (!res.ok) {
					// Se la richiesta non ha avuto successo, lanciamo un errore con lo stato della risposta
					throw new Error(`Request failed with status ${res.status}`);
				}
				return res.json();
			})
			.then((data) => {
				// Gestisci la risposta JSON qui
				if (data.status == 1) {
					notifications.success('Registrazione avvenuta con successo!', 3000);
					setTimeout(() => {
						user.update((val) => (val = { ...data.data }));
						saveUser($user);
						goto('/');
					}, 3000);
				} else currentError = data.message;

				//goto('/');
				// Esegui le azioni necessarie dopo aver ricevuto la risposta
			})
			.catch((error) => {
				// Gestisci gli errori qui
				console.log('Error logging in:', error.message);
				currentError = error.message; // Aggiorna la variabile currentError con il messaggio di errore
			});
	};
</script>

<form
	class="flex flex-col gap-y-4 items-center w-1/3 px-8 py-8 mx-auto px bg-white rounded-lg"
	on:submit|preventDefault={login}
>
	<h1 class="text-6xl text-secondary font-semibold mb-5">Registrati</h1>
	<input
		type="text"
		id="username"
		bind:value={username}
		class="bg-primary w-4/5 h-12 rounded-lg text-black text-lg p-4 placeholder-black placeholder-opacity-70"
		placeholder="username"
	/>
	<input
		type="text"
		id="email"
		bind:value={email}
		class="bg-primary w-4/5 h-12 rounded-lg text-black text-lg p-4 placeholder-black placeholder-opacity-70"
		placeholder="mariorossi@gmail.com"
	/>
	<input
		type="password"
		id="password"
		bind:value={password}
		class="bg-primary w-4/5 h-12 rounded-lg text-black text-lg p-4 placeholder-black placeholder-opacity-70"
		placeholder="password"
	/>
	<button type="submit" class="bg-secondary w-3/5 h-12 rounded-lg text-white">Registrati</button>
	<LinkBtn name="indietro.." url="/" />
	{#if currentError}
		<p class="error-message">{currentError}</p>
	{/if}
</form>

<Toast />
