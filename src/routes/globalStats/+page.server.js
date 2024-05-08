export const load = ({ fetch, params }) => {
	const fetchQuiz = async () => {
		const res = await fetch(`http://localhost/backend-quiz-daver/backend-quizSvelte/get-quiz.php`);
		const data = await res.json();
		return data;
	};

	return fetchQuiz();
};
