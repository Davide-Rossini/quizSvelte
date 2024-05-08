import { goto } from '$app/navigation';

export const load = ({ fetch, params }) => {
	const fetchQuestions = async (id) => {
		const res = await fetch(`http://localhost/backend-quiz-daver/backend-quizSvelte/get-questions.php?id=${id}`);
		const data = await res.json();
		if (data.status == 0) {
			goto('/selezione');
			return;
		}

		return data;
	};

	return fetchQuestions(params.quizId);
};
