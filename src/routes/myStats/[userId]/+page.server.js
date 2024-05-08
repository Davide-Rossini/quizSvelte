import { goto } from '$app/navigation';

export const load = ({ fetch, params }) => {
	const fetchPersonalScore = async (id) => {
		let url = `http://localhost/backend-quiz-daver/backend-quizSvelte/get-personalScore.php?id=${id}`;
		const res = await fetch(url);
		const data = await res.json();
		if (data.status == 0) {
			goto('/selezione');
			return;
		}

		return data;
	};

	return fetchPersonalScore(params.userId);
};
